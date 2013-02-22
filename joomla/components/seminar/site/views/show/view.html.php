<?php
/**
 * @subpackage Seminar
 * @touch date 12/09/2012 9:00:00 AM
 * @link http://www.echoname.com
 * @version 1.0.0
 * @license GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Seminar Component
 * @subpackage Seminar
 */
 
class SeminarViewShow extends JView {
	function display($tpl = null) {   
        // For popup 
        $document =& JFactory::getDocument(); 

        $model = &$this->getModel();
        $data = $model->getLastPublished();
        
        if($data == null){
            $data['message'] = JText::_('Oops! There is not any announcement.');
        }
        
        $this->assignRef('data', $data);

        parent::display($tpl);
    }
}
