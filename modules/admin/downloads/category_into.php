<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$id = intval($_GET['id']);

$cat_down = $db->super_query ("SELECT * FROM downloads_category WHERE id = '$id'");

if ($cat_down['name']) {
    if ($_GET['is'] == "up") {
        $position = $cat_down['position'];
        $position = $position + 1;
    } else {
        $position = $cat_down['position'];
        $position = $position-1;
    } 

    $db->query ("UPDATE downloads_category SET position = '$position' WHERE id = '$id'");
} 

@header ('Location: ' . $_SERVER['HTTP_REFERER']);
exit;

?>