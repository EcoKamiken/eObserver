<?php

/**
 * 汎用的な関数
 */

function load_template($file)
{
    ob_start();
    include $file;
    $html = ob_get_contents();
    ob_end_clean();

    echo $html;
}

function get_array($sql)
{
    $pdo = get_pdo();
    $stmt = $pdo->query($sql);
    if ($stmt) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "ERROR";
    }
}

/**
 * 与えられたデータをJSON形式の文字列に変換して返す
 * 変換の際に以下の文字はエスケープ処理を行う
 * ['<', '>', '&', ''', '"']
 *
 * @param  string $json_str
 * @return string
 */
function json_safe_encode($json_str)
{
    return json_encode($json_str, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

/**
 * 文字列または文字列の配列を受け取り、
 * 特殊文字をHTMLエンティティに変換したものを返す
 *
 * @param  string $raw_data
 * @return string|array
 */
function escape_special_characters($raw_data)
{
    if (is_array($raw_data)) {
        return array_map("escape_special_characters", $raw_data);
    } else {
        return htmlspecialchars($raw_data, ENT_QUOTES, 'UTF-8');
    }
}
