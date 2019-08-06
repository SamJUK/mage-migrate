<?php

require_once BASE_DIRECTORY.'/src/registry.php';

trait database 
{
    public $using_database = true;
    public $can_use_database = true;
    public $db_error_messages = [];
    
    private $reg;
    private $dest_pdo;
    private $src_pdo;

    public function __construct()
    {
        $this->reg = registry::getInstance();
        $this->validateDB();
    }

    public function validateDB()
    {
        try {
            $cfg = $this->reg->get('config');
            $this->createDesinationPDO($cfg->global->database->desination);
            $this->createSourcePDO($cfg->global->database->source);
        } catch (Exception $e) {
            $this->can_use_database = false;
            $this->db_error_messages[] = $e->getMessage();
        }
    }

    public function canUseDatabase()
    {
        return $this->can_use_database;
    }

    public function createDesinationPDO($data)
    {
        $this->dest_pdo = $this->createPDO($data);
        return $this->dest_pdo;
    }

    public function createSourcePDO($data)
    {
        $this->src_pdo = $this->createPDO($data);
        return $this->src_pdo;
    }

    public function createPDO($data)
    {
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $dsn = "mysql:host={$data['host']};dbname={$data['name']};charset=utf8mb4";
        try {
             return new \PDO($dsn, $data['user'], $data['pass'], $options);
        } catch (\PDOException $e) {
             throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getDestinationDatabase()
    {
        return $this->dest_pdo;
    }

    public function getSourceDatabase()
    {
        return $this->src_pdo;
    }


}