<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ////////////////////////////
// ///// Подключаем класс базы
require_once ENGINE_DIR . '/classes/db.class.php';
// ////////////////////////////
// ///// Подключаем счетчик
require_once ENGINE_DIR . '/classes/microTimer.class.php';
// ////////////////////////////
// ///// Подключаем класс обработки скриншотов
require_once ENGINE_DIR . '/classes/thumbnail.class.php';
// ////////////////////////////
// ///// Подключаем шаблонизатор
require_once ENGINE_DIR . '/classes/tpls.class.php';
require_once ENGINE_DIR . '/classes/page.class.php';
require_once ENGINE_DIR . '/classes/comments.class.php';

?>