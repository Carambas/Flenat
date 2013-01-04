<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

if ($user_id['grup'] == 1) $moder = true;

switch ($_GET['act']) {
    case "add" :
        include ROOT_DIR . '/modules/book/add.php';
        break;
    case "admin" :
        include ROOT_DIR . '/modules/book/admin.php';
        break;

    default :
        include ROOT_DIR . '/modules/book/book.php';
        break;
} 

?>