<?php

require('../core/database.php');

$connection = new Database\Database();
$stmt = $connection->dbh->query('SELECT * from sites');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_UNIQUE);
var_dump($rows);
