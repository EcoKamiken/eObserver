<?php

require('../core/database.php');
require('../core/functions.php');
require('../core/common.php');

$sanitized_post = escape_special_characters($_POST);
$date = new common\Date();

require('parts/header.php');
require('parts/datepicker.php');

?>

  <main class="layout">

<?php

$sql = "
SELECT
  id, name, capacity, machine_type
FROM
  sites
ORDER BY
  grp, serial_number
";

$connection = new Database\Database();
$stmt = $connection->dbh->query($sql);
$sites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- foreach begin -->
<?php
foreach ($sites as $row) {
    ?>

    <a href="detail.php?id=<?php echo $row['id']; ?>&today=<?php echo $date->begin_date; ?>">
      <section class='chartContainer'>
        <canvas id='<?php echo $row['id']; ?>'></canvas>
      </section>
    </a>

    <?php
}
?><!-- foreach end -->

  </main>

<?php

require('parts/footer.php');

// データを取得して、mainの中で生成したキャンバスにグラフを描画する。
foreach ($sites as $row) {
    $sql = "
    select
        date_format(created_at, '%Y-%m-%d %H:00:00') as c_at,
        round(sum(wattage)/count(*), 2) as w_avg
    from
        sensors
    where
        created_at between :begin_date and :end_date
        and id = :id
    group by
        c_at
    ";

    $stmt = $connection->dbh->prepare($sql);
    $stmt->bindValue(':begin_date', $date->begin_date.' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':end_date', $date->end_date.' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':id', (int)$row['id'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $name = $row['name'] . " " . $row['capacity'] . "kW";
    $id = $row['id'];

    $json = json_safe_encode($result);
    $result = null;
    echo "\n<script>drawGraph('$name', '$id', '$json', false);</script>";
}
?>
