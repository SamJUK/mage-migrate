<?php

require_once BASE_DIRECTORY.'/src/module.php';
require_once BASE_DIRECTORY.'/src/traits/database.php';

class cms_pages extends module
{
    use database;

    public function getName()
    {
        return 'Migrate static pages via MySQL';
    }

    public function execute()
    {
        core::output('Starting static page migration');
        parent::execute();
    }

}