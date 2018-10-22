<?php
  include 'parts/header.php';
  include '../core/mysql.php';
  include '../core/functions.php';

  $_POST = sethtmlspecialchars($_POST);
  $today = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
  if(!isset($today)) {
    $today = date("Y-m-d");
  }
  $month = date('m', strtotime("$today", time()));
  $tommorow = date("Y-m-d", strtotime("$today +1 day", time()));

  echo $_POST['site_id'];
  echo $_POST['today'];
  echo $today;
  echo $tommorow;
?>

  <h1 class="subtitle"><?php echo $day ?>のデータ</h1>
  <main>

<?php
  
?>

    <table width="100%" cellspacing="0" cellpadding="0" bordercolor="#fff">
      <tr>
        <td>本日の発電量</td>
        <td><?php echo $today ?>kWh</td>
      </tr>
      <tr>
        <td><?php echo $month ?>月の発電量</td>
        <td><?php echo $total ?>kWh</td>
      </tr>
    </table>
  </main>

<?php
  include '../parts/footer.php';
?>