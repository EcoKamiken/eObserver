<?php
  include 'parts/header.php';
  include '../core/mysql.php';
  include '../core/functions.php';

  $_POST = sethtmlspecialchars($_POST);
  $today = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
  if(!isset($today)) {
    $today = filter_input(INPUT_GET, 'today', FILTER_SANITIZE_STRING);
    if(!isset($today)) {
      $today = date("Y-m-d");
    }
  }
  $tommorow = date("Y-m-d", strtotime("$today +1 day", time()));

  $id = $_GET['id'];
  $pdo = get_pdo();
  $stmt = $pdo->prepare('select name from sites where id = :id');
  $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
  $stmt->execute();
  $res = $stmt->fetch();
  $sitename =  $res['name'];

  include 'parts/date_picker.php';
?>

  <h1 class="title card"><?php echo $sitename ?> 太陽光発電所</h1>
  <main class="layout">

<?php
  $pdo = get_pdo();
  $stmt = $pdo->prepare('select name, device_qty from sites where id = :id');
  $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
  $stmt->execute();
  $res = $stmt->fetch();
  foreach(range(0, $res['device_qty'] - 1) as $device_id) {
?>

    <section class='chartContainer'>
      <canvas id="<?php echo $device_id; ?>"></canvas>
    </section>

<?php
  }

  $pdo = get_pdo();
  $stmt = $pdo->prepare('select name, device_qty from sites where id = :id');
  $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
  $stmt->execute();
  $res = $stmt->fetch();

  $today_wattage = 0;
  $month_wattage = 0;
  $year_wattage = 0;
  foreach(range(0, $res['device_qty'] - 1) as $device_id) {

    $sql = "
      select 
          date_format(created_at, '%Y-%m-%d %H:00:00') as times,
          round(sum(wattage)/count(*), 2) as wattage
      from
          sensors
      where
          created_at between :today and :tommorow
          and id = :id
          and device_id = :device_id
      group by
          times; 
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':today', $today.' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':tommorow', $tommorow.' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->bindValue(':device_id', (int)$device_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    foreach($result as $value) {
      $today_wattage += (float)$value['wattage'];
    }

    $sql = "
      select 
          date_format(created_at, '%Y-%m-%d %H:00:00') as times,
          round(sum(wattage)/count(*), 2) as wattage
      from
          sensors
      where
          created_at between :beginning and :end
          and id = :id
          and device_id = :device_id
      group by
          times; 
    ";


    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':beginning', substr($today, 0, 7).'-1'.' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':end', substr($today, 0, 7).date('-t').' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->bindValue(':device_id', (int)$device_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    foreach($result as $value) {
      $month_wattage += (float)$value['wattage'];
    }

    $sql = "
      select 
          date_format(created_at, '%Y-%m-%d %H:00:00') as times,
          round(sum(wattage)/count(*), 2) as wattage
      from
          sensors
      where
          created_at between :begin and :end
          and id = :id
          and device_id = :device_id
      group by
          times; 
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':begin', substr($today, 0, 4).'-01-01 '.' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':end', substr($today, 0, 4).'-12-31'.' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->bindValue(':device_id', (int)$device_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    foreach($result as $value) {
      $year_wattage += (float)$value['wattage'];
    }
  }
?>

  </main>

  <div class="detail card">
    <table>
      <tr>
        <th>データ</th>
      </tr>
      <tr>
        <th>本日の発電量</td>
        <td><?php echo round($today_wattage, 2); ?>[kWh]</td>
      </tr>
      <tr>
        <th>今月の発電量</td>
        <td><?php echo round($month_wattage, 2); ?>[kWh]</td>
      </tr>
      <tr>
        <th>今年の発電量</td>
        <td><?php echo round($year_wattage, 2); ?>[kWh]</td>
      </tr>
    </table>
  </div>

<?php
  include 'parts/footer.php';


  // データを取得して、mainの中で生成したキャンバスにグラフを描画する。
  $pdo = get_pdo();
  $stmt = $pdo->prepare('select name, device_qty from sites where id = :id');
  $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
  $stmt->execute();
  $res = $stmt->fetch();
  foreach(range(0, $res['device_qty'] - 1) as $device_id) {

    $sql = "
    select
      created_at as times,
      temperature,
      humidity,
      round(wattage, 2) as wattage
    from
        sensors
    where
        created_at between :today and :tommorow
        and id = :id
        and device_id = :device_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':today', $today.' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':tommorow', $tommorow.' 00:00:00', PDO::PARAM_STR);
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->bindValue(':device_id', (int)$device_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    $json = json_safe_encode($result);
    $name = "Device" . $device_id;
    echo "\n<script>drawGraph('$name', '$device_id', '$json', true);</script>";
  }
?>
