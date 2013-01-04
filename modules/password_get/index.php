<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ///////////////////////////////////////////////////////////////
// //
// //
// ///////////////////////////////////////////////////////////////
switch ($_REQUEST['act_password_get']) {
    case "1" :
        include ROOT_DIR . '/modules/password_get/1.php';
        break;
    case "2" :
        include ROOT_DIR . '/modules/password_get/2.php';
        break;
    case "3" :
        include ROOT_DIR . '/modules/password_get/3.php';
        break;
    case "4" :
        include ROOT_DIR . '/modules/password_get/4.php';
        break;

    default :
        header ('Location: ' . $PHP_SELF . '?do=password_get&act_password_get=1');
        exit;
} 

?>