<?php
  include 'parts/header.php';
  include '../core/mysql.php';
  include '../core/functions.php';

  $day = $_GET["day"];
  $month = date('m', strtotime("$day", time()));
  $tommorow = date('Y-m-d', strtotime("$day +1 day", time()));
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