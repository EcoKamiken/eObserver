<?php

require('../core/database.php');

$connection = new Database\Database();
$stmt = $connection->dbh->query('SELECT * from sites');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_UNIQUE);
var_dump($rows);

$sql = "
select
    date_format(created_at, '%Y-%m-%d %H:00:00') as c_at,
    round(sum(wattage)/count(*), 2) as w_avg
from
    sensors
where
    created_at between :begin_date and :end_data
    and id = :id
group by
    c_at
";

try {
    echo $date->begin_date;
    echo $date->end_date;
    echo $row['id'];
    $stmt = $connection->dbh->prepare($sql);
    $stmt->bindValue(':begin_date', $date->begin_date.' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':end_date', $date->end_date.' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':id', (int)$row['id'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    var_dump($result);
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}