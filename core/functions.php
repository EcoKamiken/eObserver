<?php

function load_template($file) {
    ob_start();
    require $file;
    $html = ob_get_contents();
    ob_end_clean();

    echo $html;
}

function get_array($sql) {
    $pdo = get_pdo();
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function json_safe_encode($data) {
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

function draw_overview() {
    $sql = 'select distinct place from thermometer';
    $stmt = get_statement($sql);

    $date = date('Y-m-d');
    foreach($stmt as $row) {
        $place = $row['place'];
        $sql = "SELECT * FROM thermometer JOIN wattage
                ON thermometer.date = wattage.date
                WHERE thermometer.place='$place' AND wattage.place='$place' AND thermometer.date BETWEEN '$date 00:00:00' AND '$date 23:59:59' AND thermometer.date=TIME_FORMAT(thermometer.date, '$date %H:00:00')";
        $dataPoints = get_array($sql);
        $dataPointsJson = json_safe_encode($dataPoints);
        print("<script>drawGraph($dataPointsJson);</script>");
    }
}