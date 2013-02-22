<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class SeminarControllerHistory extends JController{
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

        $this->view->assignRef('data', $data);

        $this->view->setLayout('form');
        $this->view->display();
    }
/*}}}*/
}
