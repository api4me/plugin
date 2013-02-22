<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class SeminarControllerShow extends JController{
/*{{{ construct */
    function __construct($config = array()){
        parent::__construct($config);

        $control = JRequest::getVar('_control_');
        $this->view = $this->getView($control, 'html');
        $this->model = $this->getModel($control);
        $this->registerTask('apply',  'save');
    }
/*}}}*/
/*{{{ display */
    function display() {
        $option = JRequest::getCmd('option');

//        JRequest::setVar('hidemainmenu', 1);

        $control = JRequest::getVar('_control_');
        $text = JText::_("Announcement");
        JToolBarHelper::title(JText::_('Seminar') .': <small><small>[ '. $text.' ]</small></small>', 'article.png');
        JToolBarHelper::apply($control . '.save', "Save as Draf");
        JToolBarHelper::save($control . '.publish', "Save & Publish");
        JToolBarHelper::divider();
        JToolBarHelper::preferences('com_seminar');
        $data = $this->model->load();
        if ($data->status == '2') {
            JToolBarHelper::divider();
            JToolBarHelper::custom($control . '.send', 'send.png', 'send_f2.png', 'Send email', false);
        } else {
            $link = "index.php?option={$option}&load=true";
            $this->view->assignRef('notice', JText::sprintf("Current is draf, please click \"Save & Publish\" button, then \"Send email\" button will be appeared. If load template, please click <a href=\"%s\"> here </a>.", $link));
        }

        // Check for post data in the event that we are returning
        // from a unsuccessful attempt to save data
        $post = JRequest::get('post');
        if ($post) {
            $data =& $this->model->getTable();
            $data->bind($post);
            $data->content = JRequest::getVar('content', '', 'post', 'string', JREQUEST_ALLOWRAW);
        }

        $this->view->assignRef('data', $data);
        $this->view->assignRef('option', $option);
        $this->view->assignRef('task', $control);

        // content
        $editor =& JFactory::getEditor();
        // parameters : areaname, content, width, height, cols, rows, show xtd buttons
        $content = $editor->display('content', htmlspecialchars($data->content, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore', 'article', 'image'));
        $this->view->assignRef('content', $content);

        // History List
        $history = $this->model->getHistory();
        $this->view->assignRef('history', $history);

        $this->view->setLayout('form');
        $this->view->display();
    }
/*}}}*/
/*{{{ save */
    function save() {
        $option = JRequest::getCmd('option');
        $app = JFactory::getApplication('administrator');

        // Check for request forgeries
        JRequest::checkToken() or die('Invalid Token');

        $control = JRequest::getVar('_control_');
        switch ($this->getTask()) {
            case 'save':
                if (!$this->model->save()) {
                    return $this->execute('display');
                }
                break;
            case 'publish':
                if (!$this->model->publish()) {
                    return $this->execute('display');
                }
            default:
                break;
        }
        $app->enqueueMessage(JText::_('Announcement Saved'));
        $link = "index.php?option={$option}";
        $app->redirect($link);
    }
/*}}}*/
/*{{{ publish */
    function publish() {
        $option = JRequest::getCmd('option');
        $app = JFactory::getApplication('administrator');

        // Check for request forgeries
        JRequest::checkToken() or die('Invalid Token');

        if ($this->getTask() == 'publish') {
            $this->model->publish();
        } else {
            $this->model->unpublish();
        }
        $control = JRequest::getVar('_control_');

        $app->redirect("index.php?option={$option}&task={$control}");
    }
/*}}}*/
/*{{{ send */
    function send() {
        $option = JRequest::getCmd('option');
        $app = JFactory::getApplication('administrator');
        if(!$data = $this->model->getLastPublished()) {
            $app->enqueueMessage(JText::_("There is no published announcement. Please edit and publish, try it again."), 'error');
            return $this->execute('display');
        }

        // Mail
        $params = &JComponentHelper::getParams('com_seminar');
        $from = $params->get('mailfrom');
        $to = $params->get('mailto');
        if (empty($from) || empty($to)) {
            $app->enqueueMessage(JText::_("Mail from or mail to has not been setted. Please click \"Options\" button to set"), 'error');
            return $this->execute('display');
        }

        $mailer =& JFactory::getMailer();
        $mailer->setSender(array($from));

        $recipient = explode("\n", $to);
        $mailer->addRecipient($recipient);
        $mailer->isHTML(true);
        $mailer->setSubject("ACLS Seminar on this " . date("l, M. d, Y", strtotime($data->started)));
        $mailer->setBody($data->content);
        $send = &$mailer->Send();
        if ($send !== true) {
            $app->enqueueMessage(JText::_("Mail fail to send"), 'error');
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
        $q = "UPDATE #__acls_seminar set mailto='" . serialize($mailinfo) . "' WHERE id=" . $data->id;
        $db->setQuery($q);
        $db->execute();
        $app->enqueueMessage(JText::_('Mail success to send'));
        $link = "index.php?option={$option}";
        $app->redirect($link);
    }
/*}}}*/
}
