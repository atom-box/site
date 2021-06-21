<?php
// DBTransaction.php

// keep passwords in a separate file; list that file in .gitignore
require_once('./core/config/dbconfig.inc.php');

class DBTransaction {
    protected $pdo;
    public $last_insert_id;

    public function __construct(){
        define('DB_NAME', NAMEOFDATABASE);
        define('DB_USER', USER);
        define('DB_PASSWORD', SECRET);
        define('DB_HOST', 'localhost');

        $this->pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function startTransaction()
    {
        $this->pdo->beginTransaction();
    }
    
    public function insertQuery(string $sql, array $data)
    {
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->execute($data);

        $this->last_insert_id = $this->pdo->lastInsertId();
    }

    public function submitTransaction()
    {
        try {
            $this->pdo->commit();
        } catch(PDOException $e) {
            echo $e->getMessage();
            var_dump($e);
            // $this->pdo->rollBack();
            return false;
        }

        return true;
    }
}
