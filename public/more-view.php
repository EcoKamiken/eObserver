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

  include 'parts/date_picker.php';
?>

  <main>
    <div class="card device_list">
      <ul>
        <li><a href="#">デバイス0</a></li>
        <li><a href="#">デバイス1</a></li>
        <li><a href="#">デバイス2</a></li>
      </ul>
    </div>
    <div class="card moreChartContainer">
      <canvas id="chart"></canvas>
    </div>
    <div class="card detail">
      <table>
        <tr>
          <td>本日の発電量</td>
          <td>10kWh</td>
        </tr>
        <tr>
          <td>今月の発電量</td>
          <td>100kWh</td>
        </tr>
      </table>
    </div>
  </main>



<?php
  include 'parts/footer.php';
?>