<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

switch ($_REQUEST['act']) {
    case "1" :
        include ROOT_DIR . '/modules/registration/1.php';
        break;

    case "2" :
        include ROOT_DIR . '/modules/registration/2.php';
        break;

    default :
        header ('Location: ' . $PHP_SELF . '?do=registration&act=1');
        exit;
} 

?>