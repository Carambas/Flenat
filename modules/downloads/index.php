<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ///////////////////////////////////////////////////////////////
// //
// //
// ///////////////////////////////////////////////////////////////
if ($user_id['grup'] == 1) $moder = true;

switch ($_GET['act']) {
    case "admin" :
        include ROOT_DIR . '/modules/downloads/admin.php';
        break;

    case "get_file" :
        include ROOT_DIR . '/modules/downloads/get_file.php';
        break;

    case "file_info" :
        include ROOT_DIR . '/modules/downloads/file_info.php';
        break;

    case "add_comm" :
        include ROOT_DIR . '/modules/downloads/add_comm.php';
        break;
    case "del_comm" :
        include ROOT_DIR . '/modules/downloads/del_comm.php';
        break;
    default :
        include ROOT_DIR . '/modules/downloads/down.php';
} 

?>