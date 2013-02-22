<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class SeminarModelTemplate extends JModel {
/*{{{ load */
    function load() {
        $app = JFactory::getApplication('administrator');

        $q = 'SELECT * FROM #__acls_seminar WHERE status=0 ORDER BY id DESC LIMIT 1';
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
/*{{{ save */
    /**
    * Method to store a record
    *
    * @access  public
    * @return  boolean True on success
    */
    function save() {   
        $app = JFactory::getApplication('administrator');
        $row =& $this->getTable("show"); 
        $data = JRequest::get('post');

        // Bind the form fields to the hello table
        if (!$row->bind($data)) {
            $app->enqueueMessage($row->getError(), 'error');
            return false;
        }

        // Make sure the hello record is valid
        if (!$row->check()) {
            $app->enqueueMessage($row->getError(), 'error');
            return false;
        }

        $row->content = JRequest::getVar('content', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $date = JFactory::getDate();
        // Get saveed
        $q = 'SELECT * FROM #__acls_seminar WHERE status=0 ORDER BY id DESC LIMIT 1';
        $db = JFactory::getDBO();
        $db->setQuery($q);
        if ($tmp = $db->loadObject()) {
            $row->id = $tmp->id;
        } else {
            $row->id = null;
            $row->created = $date->toMySQL();
        }
        $row->updated = $date->toMySQL();
        $row->status = 0;

        // Store the web link table to the database
        if (!$row->store()) {
            $app->enqueueMessage($row->getError(), 'error');
            return false;
        }

        return true;
    }
/*}}}*/
}
