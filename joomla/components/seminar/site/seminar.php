<?php
/**
 * @subpackage Seminar
 * @touch date 02/19/2013 9:00:00 AM
 * @link www.echoname.com
 * @version 1.0.0
 * @license GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$cmd = JRequest::getCmd('task', 'show.');
list($control, $task) = explode('.', $cmd);

$view = JRequest::getCmd('view');
if ($view) {
    $control = $view;
}
switch($control) {
    default:
        $control = 'show';
        break;
    /*
    case 'press':
        break;
    */
}

// Set var in JRequest
JRequest::setVar('_control_', $control);
JRequest::setVar('_task_', $task);

require_once(JPATH_COMPONENT. DS . 'controllers' . DS . $control . '.php');
$controlName = 'SeminarController' . ucfirst($control);

// Create the controller
$controller = new $controlName();

// Perform the Request task
$controller->execute($task);
// Redirect if set by the controller
$controller->redirect();
