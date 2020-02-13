<?php

namespace Database;

use \PDO;

class Database
{
    public $dbh;

    public function __construct()
    {
        self::setDatabaseHandler();
    }

    /**
     * クラス変数$dbhにDBへの接続情報を設定する。
     * 接続情報は環境変数から取得する。
     */
    private function setDatabaseHandler()
    {
        $DB_HOST = getenv('OBS_DB_HOST');
        $DB_NAME = getenv('OBS_DB_NAME');
        $DB_USER = getenv('OBS_DB_USER');
        $DB_PASS = getenv('OBS_DB_PASS');

        try {
            $dsn = sprintf("mysql:host=%s;dbname=%s", $DB_HOST, $DB_NAME);
            $this->dbh = new PDO($dsn, $DB_USER, $DB_PASS);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}
