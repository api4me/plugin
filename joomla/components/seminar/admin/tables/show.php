<?php

class TableShow extends JTable {
    /**
    * Primary Key
    *
    * @var int
    */
    var $id = null;
    /**
    * @var string
    */
    var $started = null;
    /**
    * @var string
    */
    var $content = null;
    /**
    * @var int
    */
    var $status = null;
    /**
    * @var datatime
    */
    var $created = null;
    /**
    * @var datatime
    */
    var $updated = null;

    /**
    * Constructor
    *
    * @param object Database connector object
    */
    function TableShow(& $db) {
        parent::__construct('#__acls_seminar', 'id', $db);
    }

    function check() {
        // 1. content
        if (trim($this->content) == '') {
             $this->setError(JText::_('Please fill out content.'));
             return false;
        }

        return true;
    }
}