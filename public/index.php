<?php

require('../core/database.php');
require('../core/functions.php');
require('../core/common.php');

include('parts/header.php');

$sanitized_post = escape_special_characters($_POST);
$date = new common\Date();

require('parts/datepicker.php');
?>

  <main class="layout">

<?php

// sitesテーブルに登録されている案件を読み取り、グラフ表示のためのカードを生成する。
$sql = 'select id, name, capacity, device_qty from sites ORDER BY grp, serial_number';
$sites = get_array($sql);
echo $sites;
foreach ($sites as $row) {
    if ($row['id'] == 0) {
        continue;
    }
    ?>
    <a href="more-view.php?id=<?php echo $row['id']; ?>&today=<?php echo $today; ?>">
      <section class='chartContainer'>
        <canvas id='<?php echo $row['id']; ?>'></canvas>
      </section>
    </a>
    <?php
}
?>
  </main>

<?php
include 'parts/footer.php';

// データを取得して、mainの中で生成したキャンバスにグラフを描画する。
/*
$pdo = get_pdo();
foreach ($sites as $row) {
    $sql = "
    select
        date_format(created_at, '%Y-%m-%d %H:00:00') as times,
        round(sum(temperature)/count(*), 0) as temperature,
        round(sum(humidity)/count(*), 0) as humidity,
        round(sum(wattage)/count(*), 2) as wattage
    from
        sensors
    where
        created_at between :today and :tommorow
        and id = :id
        and device_id = :device_id
    group by
        times
    ";

    foreach(range(0, $row['device_qty'] - 1) as $device_id) {
      $id = $row['id'];
      $name = $row['name'] . " " . $row['capacity'] . "kW";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':today', $today.' 00:00:00', PDO::PARAM_STR);
      $stmt->bindValue(':tommorow', $tommorow.' 00:00:00', PDO::PARAM_STR);
      $stmt->bindValue(':id', (int)$row['id'], PDO::PARAM_INT);
      $stmt->bindValue(':device_id', $device_id, PDO::PARAM_INT);
      $stmt->execute();
      if(!$result) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
        $tmp = $stmt->fetchAll(PDO::FETCH_ASSOC);
        for($i = 0; $i < count($result); $i++) {
          $result[$i]['wattage'] += $tmp[$i]['wattage'];
        }
      }
    }
    $json = json_safe_encode($result);
    $result = null;
    echo "\n<script>drawGraph('$name', '$id', '$json', false);</script>";
}
*/
?>
