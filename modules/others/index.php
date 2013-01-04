<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ///////////////////////////////////////////////////////////////
// //
// //
// ///////////////////////////////////////////////////////////////
switch ($_REQUEST['act']) {
    case "bbcode" :
        include ROOT_DIR . '/modules/others/bbcode.php';
        break;
    case "smiles" :
        include ROOT_DIR . '/modules/others/smiles.php';
        break;

    default :
        info('Ошибка', 'Недопустимый запрос!');
} 

?>