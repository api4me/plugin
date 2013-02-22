<?php

class TableShow extends JTable {
    /**
    * Primary Key
    *
    * @var int
    */
    var $id = null;
    /**
    * @var datatime
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
        // 1. started
        if ($this->status != 0) {
            if (trim($this->started) == '') {
                $this->setError(JText::_('Please fill out date of seminar.'));
                return false;
            } else {
                $started = strtotime($this->started);
                if ($started === false || $started == -1) {
                    $this->setError(JText::_('The date of seminar is not correct, please check it.'));
                    return false;
                }
                if ($started < strtotime(strftime("%Y-%m-%d"))) {
                    $this->setError(JText::_('The date of seminar should greater than taday, please check it.'));
                    return false;
                }
            }
        }
        // 2. content
        if (trim($this->content) == '') {
             $this->setError(JText::_('Please fill out content.'));
             return false;
        }

        return true;
    }
}
