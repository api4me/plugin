<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class SeminarControllerTemplate extends JController{
/*{{{ construct */
    function __construct($config = array()){
        parent::__construct($config);

        $control = JRequest::getVar('_control_');
        $this->view = $this->getView($control, 'html');
        $this->model = $this->getModel($control);
    }
/*}}}*/
/*{{{ display */
    function display() {
        $option = JRequest::getCmd('option');

        $data = $this->model->load();
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
        $control = JRequest::getVar('_control_') . ".save";
        $this->view->assignRef('task', $control);

        // content
        $editor =& JFactory::getEditor();
        // parameters : areaname, content, width, height, cols, rows, show xtd buttons
        $content = $editor->display('content', htmlspecialchars($data->content, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore', 'article', 'image'));
        $this->view->assignRef('content', $content);

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
        if (!$this->model->save()) {
            return $this->execute('display');
        }
        $app->enqueueMessage(JText::_('Application Saved'));
        $link = "index.php?option={$option}&task={$control}&tmpl=component";
        $app->redirect($link);
    }
/*}}}*/
}
