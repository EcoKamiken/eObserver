<?php
  include 'parts/header.php';
  include '../core/mysql.php';
  include '../core/functions.php';

  $_POST = sethtmlspecialchars($_POST);
  $today = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
  if(!isset($today)) {
    $today = date("Y-m-d");
  }
  $tommorow = date("Y-m-d", strtotime("$today +1 day", time()));

  include 'parts/date_picker.php';
?>

  <!--
  <div class="register_field">
    <a class="register" href="register.php">＋発電所を登録</a>
  </div>
  -->

  <main class="layout">

<?php
  // sitesテーブルに登録されている案件を読み取り、グラフ表示のためのカードを生成する。
  // FIXME: Loopで処理すると直観的ではないような気がするので、他にいい書き方があれば修正する。
  $sql = 'select id, name, capacity from sites ORDER BY grp, serial_number'; #     SQL ANTI PATTERN
  $sites = get_array($sql);
  foreach($sites as $row) {
    if($row['id'] == 0) continue;
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
  // FIXME: 上と同様の理由でループで処理するのがあまり直観的ではないと思うので、違う書き方がないか検討する
  $pdo = get_pdo();
  foreach($sites as $row) {
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
        and device_id = 0
    group by
        times;
    ";

    $id = $row['id'];
    $name = $row['name'] . " " . $row['capacity'] . "kW" . " " . $id;

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':today', $today.' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':tommorow', $tommorow.' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':id', (int)$row['id'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    $json = json_safe_encode($result);
    echo "\n<script>drawGraph('$name', '$id', '$json', false);</script>";
  }
?>