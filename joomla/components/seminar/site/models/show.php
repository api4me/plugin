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

jimport( 'joomla.application.component.model' );

/**
 * Show Controller
 * @subpackage Seminar
 */
class SeminarModelShow extends JModel {

/*{{{ getLastPublished */
    function getLastPublished() {
       $db =& JFactory::getDBO();
       $query = "SELECT * FROM #__acls_seminar WHERE status=2 ORDER BY updated DESC LIMIT 1 ";
       $db->setQuery($query);
       $data = $db->loadAssoc();
       return $data;
    }
/*}}}*/

}
