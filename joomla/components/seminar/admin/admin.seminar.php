<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$cmd = JRequest::getCmd('task', 'show.edit');
list($control, $task) = explode('.', $cmd);

switch($control) {
    default:
        $control = 'show';
        break;
    case 'template':
        $control = 'template';
        break;
    case 'history':
        $control = 'history';
        break;
    case 'preview':
        $control = 'preview';
        break;
}

// Set var in JRequest
JRequest::setVar('_control_', $control);
JRequest::setVar('_task_', $task);

// Require the base controller
require_once(JPATH_COMPONENT . DS . 'controllers' . DS . $control . '.php');

// Create the controller
$controlName = 'Seminar' . 'Controller' . $control;
$controller = new $controlName();

// Perform the Request task
$controller->execute($task);
// Redirect if set by the controller
$controller->redirect();

?>
