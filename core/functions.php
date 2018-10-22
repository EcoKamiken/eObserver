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

function sethtmlspecialchars($data) {
    if(is_array($data)) {
        return array_map("sethtmlspecialchars", $data);
    } else {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}