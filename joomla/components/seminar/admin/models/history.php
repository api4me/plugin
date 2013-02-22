<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class SeminarModelHistory extends JModel {
/*{{{ load */
    function load() {
        $app = JFactory::getApplication('administrator');

        $id = JRequest::getVar("id", "", "get", "int");
        $q = "SELECT * FROM #__acls_seminar WHERE status=2 AND id={$id}";
        $db = JFactory::getDBO();
        $db->setQuery($q);
        $row = $db->loadObject();
        if ($db->getErrorNum()) {
            $app->enqueueMessage($db->getErrorMsg(), 'error');
            return false;
        }
        return $row;
    }
/*}}}*/
}
