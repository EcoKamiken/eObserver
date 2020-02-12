<?php

class Database
{
    public $dbh;

    public function __construct()
    {
        $this->dbh = $this->getDatabaseHandler();
    }

    private function getDatabaseHandler()
    {
        $DB_HOST = getenv('OBS_DB_HOST');
        $DB_NAME = getenv('OBS_DB_NAME');
        $DB_USER = getenv('OBS_DB_USER');
        $DB_PASS = getenv('OBS_DB_PASS');

        try {
            $dsn = sprintf("mysql:host=%s;dbname=%s", $DB_HOST, $DB_NAME);
            print($dsn);
            $dbh = new PDO($dsn, $DB_USER, $DB_PASS);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbh;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}
