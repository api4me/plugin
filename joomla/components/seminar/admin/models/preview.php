<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class SeminarModelPreview extends JModel {
/*{{{ getLastPublished */
    function getLastPublished() {
        $app = JFactory::getApplication('administrator');

        $q = 'SELECT * FROM #__acls_seminar WHERE status=2 ORDER BY id DESC LIMIT 1';
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
