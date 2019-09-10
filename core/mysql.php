<?php

function get_pdo() {
    $DB_HOST = getenv('OBS_DB_HOST');
    $DB_NAME = getenv('OBS_DB_NAME');
    $DB_USER = getenv('OBS_DB_USER');
    $DB_PASS = getenv('OBS_DB_PASS');

    echo $DB_HOST.$DB_NAME;

    try {
        $dsn = sprintf("mysql:host=%s;dbname=%s", $DB_HOST, $DB_NAME);
        return new PDO($dsn, $DB_USER, $DB_PASS);
    } catch(PDOException $e) {
        exit($e->getMessage());
    }
}
