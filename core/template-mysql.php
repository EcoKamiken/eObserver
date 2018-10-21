<?php

function get_pdo() {
    define("DB_HOST", "127.0.0.1:3306");
    define("DB_NAME", "database_name");
    define("DB_USER", "username");
    define("DB_PASS", "password");

    try {
        $dsn = sprintf("mysql:host=%s;dbname=%s", DB_HOST, DB_NAME);
        return new PDO($dsn, DB_USER, DB_PASS);
    } catch(PDOException $e) {
        exit($e->getMessage());
    }
}