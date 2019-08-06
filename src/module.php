<?php

abstract class module
{
    abstract function getName();

    public function execute()
    {
        $using_db = isset($this->using_database) && $this->using_database;
        $db_valid = false;
        $messages = is_array($this->db_error_messages)
            ? implode(';', $this->db_error_messages)
            : $this->db_error_messages;

        if ($using_db && !$db_valid) {
            core::output("DB ERRORS: $messages".PHP_EOL);
            return false;
        }
    }

}