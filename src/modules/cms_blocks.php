<?php

require_once BASE_DIRECTORY.'/src/module.php';
require_once BASE_DIRECTORY.'/src/traits/database.php';

class cms_blocks extends module
{
    use database;
    
    public function getName()
    {
        return 'Migrate static blocks via MySQL';
    }

    public function execute()
    {
        core::output('Starting static block migration');
        parent::execute();
    }

}