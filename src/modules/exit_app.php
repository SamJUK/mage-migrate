<?php

require_once BASE_DIRECTORY.'/src/module.php';

class exit_app extends module
{
    public function getName()
    {
        return 'Exit the application';
    }

    public function execute()
    {
        exit;
    }

}