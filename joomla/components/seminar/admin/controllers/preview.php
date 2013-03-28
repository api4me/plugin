<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class SeminarControllerPreview extends JController{
/*{{{ construct */
    function __construct($config = array()){
        parent::__construct($config);

        $control = JRequest::getVar('_control_');
        $this->view = $this->getView('show','html');
        $this->model = $this->getModel($control);
    }
/*}}}*/
/*{{{ display */
    function display() {
        $option = JRequest::getCmd('option');
        $app = JFactory::getApplication('administrator');

        if(!$data = $this->model->getLastPublished()) {
            $app->enqueueMessage(JText::_("There is no published announcement. Please edit and publish, try it again."),'error');
            return false;
        }

        $control = JRequest::getVar('_control_');
        $post = JRequest::get('post');
        if ($post) {
            $data->to = $post["to"];
            $data->bcc = $post["bcc"];
            $data->subject = $post["subject"];
            $attachment = $post["attachment"];
        } else {
            $params = &JComponentHelper::getParams('com_seminar');
            $data->to = str_replace("\n", ";", $params->get('mailto'));
        }

        $this->view->assignRef('data', $data);
        $this->view->assignRef('option', $option);
        $this->view->assign('task', $control . ".send");
        $this->view->setLayout('preview');
        $this->view->display();

    }
/*}}}*/
/*{{{ send */
    function send() {
        // Check
        // ------------------------------------------------------
        jimport('joomla.mail.helper');
        jimport('joomla.filesystem.file');

        $option = JRequest::getCmd('option');
        $app = JFactory::getApplication('administrator');
        $mailer =& JFactory::getMailer();
        $post = JRequest::get('post');

        if(!$data = $this->model->getLastPublished()) {
            $app->enqueueMessage(JText::_("There is no published announcement. Please edit and publish, try it again."),'error');
            return $this->execute('display');
        }
        // Mail From
        $params = &JComponentHelper::getParams('com_seminar');
        $from = $params->get('mailfrom');
        if (empty($from)) {
            $app->enqueueMessage(JText::_("Mail from has not been setted. Please click \"Options\" button to set"),'error');
            return $this->execute('display');
        }
        $mailer->setSender(array($from));

        // Mail to
        $to = $post["to"];
        if (empty($to)) {
            $app->enqueueMessage(JText::_("Please fill out mail to address."),'error');
            return $this->execute('display');
        } else {
            $recipient = explode(";", $to);
            foreach($recipient as $key => &$val) {
                $val = trim($val);
                if(empty($val)) {
                    continue;
                }
                if (!JMailHelper::isEmailAddress($val)) {
                    $app->enqueueMessage(JText::_("Mail to address is not correct.") . " " . $val, "error");
                    return $this->execute('display');
                }
                $mailer->addRecipient($val);
            }
        }
        // Mail bcc
        $bcc = $post["bcc"];
        if (!empty($bcc)) {
            $bcc = explode(";", $bcc);
            foreach($bcc as $key => &$val) {
                $val = trim($val);
                if(empty($val)) {
                    continue;
                }
                if (!JMailHelper::isEmailAddress($val)) {
                    $app->enqueueMessage(JText::_("Mail bcc address is not correct.") . " " . $val, "error");
                    return $this->execute('display');
                }
                $mailer->addBCC($val);
            }
        }
        // Subject
        if (empty($post["subject"])) {
            $app->enqueueMessage(JText::_("Please fill out subject."), 'error');
            return $this->execute('display');
        }
        $mailer->isHTML(true);
        $mailer->setSubject(trim($post["subject"]));
        $mailer->setBody($data->content);

        // File Upload
        // ------------------------------------------------------
        $fieldName = "attachment";
        $fileError = $_FILES[$fieldName]['error'];
        // Has upload file
        if (fileError != 4) {
            if ($fileError > 0) {
                switch ($fileError) {
                    case 1:
                        $app->enqueueMessage(JText::_('File to large than php ini allows'), "error");
                        return $this->execute('display');

                    case 2:
                        $app->enqueueMessage(JText::_('File to large than html form allows'), "error");
                        return $this->execute('display');

                    case 3:
                        $app->enqueueMessage(JText::_('Error partial upload'), "error");
                        return $this->execute('display');
                }
            }
            //check for filesize
            $fileSize = $_FILES[$fieldName]['size'];
            if($fileSize > 2*1024*1024) {
                $app->enqueueMessage(JText::_('File bigger than 2M'), "error");
                return $this->execute('display');
            }
            //check the file extension is ok
            $fileName = explode('.', $_FILES[$fieldName]['name']);
            $ext = array_pop($fileName);
            // jpg, gif, png, jpeg, bmp, pdf，ppt, ppts，doc, docx, xls, xlsx，txt
            $exts = array("jpg", "gif", "png", "jpeg", "bmp", "pdf", "ppt", "ppts", "doc", "docx", "xls", "xlsx", "txt");
            if (!in_array($ext, $exts)) {
                $app->enqueueMessage(JText::_('Invalid extension'), "error");
                return $this->execute('display');
            }

            $path = JPATH_ROOT . "/images/attachment";
            if (!JFolder::exists($path)) {
                JFolder::create($path);
            }
            $fullName = sprintf("%s/%s-%s.%s", $path, preg_replace("/[^A-Za-z0-9.]/i", "-", implode(".", $fileName)), date("YmdHis"), $ext);
            if(!JFile::upload($_FILES[$fieldName]['tmp_name'], $fullName)) {
                $app->enqueueMessage(JText::_("Error moving file"), "error");
                return $this->execute('display');
            }
            $mailer->addAttachment($fullName);
        }
        // Send mail
        // ------------------------------------------------------
        $send = &$mailer->Send();
        if ($send !== true) {
            $app->enqueueMessage(JText::_("Mail fail to send"),'error');
            return $this->execute('display');
        }
        // Set the send log
        $date = JFactory::getDate();
        $mailinfo = array(
            "from" => $from,
            "to" => $to,
            "date" => $date->toMySQL(),
        );
        $db = JFactory::getDBO();
        $q = "UPDATE #__acls_seminar set mailto='" . serialize($mailinfo) . "'WHERE id=" . $data->id;
        $db->setQuery($q);
        $db->execute();
        $app->enqueueMessage(JText::_('Success to send mail.'));
        $link = "index.php?option={$option}&task=preview.success&tmpl=component";
        $app->redirect($link);
    }
/*}}}*/
/*{{{ success */
    function success() {
        $this->view->setLayout('success');
        $this->view->display();
    }
/*}}}*/
}