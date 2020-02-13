<?php

require('../core/database.php');
require('../core/functions.php');
require('../core/date.php');
require('../core/graph.php');

$sanitized_post = escape_special_characters($_POST);
$date = new Common\Date();

require('parts/header.php');
require('parts/datepicker.php');

?>

  <main class="layout">

<?php

$sql = "
SELECT
  id, name, capacity, machine_type, is_visible
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
    // is_visibleが0の場合はスキップする
    if ($row['is_visible'] == 0) {
        continue;
    }
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
    $graph = new Common\Graph;

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

    $graph->title = $row['name'] . " " . $row['capacity'] . "kW";
    $graph->id = $row['id'];
    $graph->json = json_safe_encode($result);

    echo "\n<script>drawGraph('$graph->title', '$graph->id', '$graph->json', false);</script>";
}
?>
