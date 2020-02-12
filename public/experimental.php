<?php

require('../core/database.php');
require('../core/functions.php');

$data = [
    'a' => '<script>',
    'b' => 'alert();',
    'c' => '</script>'
];

$json_data = json_safe_encode($data);

print($json_data);

$connection = new Database();
$stmt = $connection->dbh->query('SELECT * from sites');
print($stmt->fetch());
