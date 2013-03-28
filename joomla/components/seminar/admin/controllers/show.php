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
        $this->registerTask('publish',  'save');
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
            // JToolBarHelper::custom($control . '.send', 'send.png', 'send_f2.png', 'Send email', false);
            // JToolBarHelper::preview("index.php?option={$option}&tmpl=component");
            $bar = JToolBar::getInstance('toolbar');
            // Add an upload button.
            $bar->appendButton('Popup', 'preview', 'Preview', "index.php?option={$option}&tmpl=component&task=preview", 800, 600);
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
}