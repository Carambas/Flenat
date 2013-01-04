<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ///////////////////////////////////////////////////////////////
// //
// //
// ///////////////////////////////////////////////////////////////
$filesConfig['widththumb'] = intval($config['screen_xy_min']);
$config ['jpeg_quality'] = intval($config['screen_xy_quality']);
$filesConfig ['allow_watermark'] = intval($config['watermark']);
$config ['max_up_side'] = intval($config['screen_xy_max']);
$ignor_files = check_full($config['ignor_files']);
$filesConfig ['accepted_files'] = check_full($config['good_files']);
$MAX_SIZE = intval($config['files_sizes']);
$filesConfig ['accepted_files2'] = check_full($config['good_files_screen']);
$MAX_SIZE2 = intval($config['screen_sizes']);
$add_screens = intval($config['screen_add']);
// ////// Получаем данные
$add_file = array ('file_cat' => $_POST['file_cat'],
    'primer' => $_POST['primer']
    );

$is_add = 1;
// ///// Проверяем категорию
if ($add_file['file_cat'] == '0' or $add_file['file_cat'] == '') {
    $error .= 'Вы не указали категорию. ';
    $is_add++;
} else {
    $cat_app = $db->super_query("SELECT * FROM downloads_category WHERE id = '{$add_file['file_cat']}'");
    $category = intval($cat_app['id']);
} 
// ////// Проверяем выбрали ли категорию
if ($is_add == 1 and $cat_app['name'] == '') {
    $error .= 'Произошла ошибка при выборе категории. ';
    $is_add++;
} 
// /////// Проверяем список файлов
$tmp_count = $db->super_query("SELECT count(*) as count FROM tmp_files");
$db->free();
if ($is_add == 1 and $tmp_count['count'] == 0) {
    $error .= 'Произошла ошибка. Файлов для загрузки ненайдено.';
    $is_add++;
} 
// ////// Определяем по какому примеру грузить файлы
if ($add_file['primer'] == '1') {
    $primer = 1;
} else {
    if ($add_file['primer'] == '2') {
        $primer = 2;
    } else {
        $error .= 'Вы не указали пример загрузки файлов. ';
        $is_add++;
    } 
} 
// ///////// Стартуем
if ($is_add != 1) {
    info('Ошибка', $error);
} else {
    $category_name = $cat_app['name'];
    $it_title = 'Загрузка в категорию: ' . $category_name;
    // //////// Пускаем цикл
    $i = 0;
    $db_sql = $db->query("SELECT * FROM tmp_files");
    while ($str = $db->get_row($db_sql)) {
        $i++;

        $rand = rv() . '_';
        $file_ext = $str['ext'];
        $file_name = substr($str['name'], 0, strrpos($str['name'], '.'));
        $file_name_tr = totranslit($file_name);
        $file_title = check_full($file_name);
        $file_title = str_replace('_', ' ', $file_title);
        $file_title = str_replace('-', ' ', $file_title);

        $output .= '<b>Файл ' . $str['name'] . '</b><br>';
        // //////// Определяем имя файла для 2 вариантов
        if ($primer == 1) $file = $file_name;
        else $file = $file_name . $file_ext;
        // //////// Перемещаем главнй файл
        if (rename('uploads/tmp/' . $file_name . $file_ext, 'uploads/downloads/files/' . $rand . $file_name_tr . $file_ext)) {
            $output .= '<small> - Загружен как ' . $rand . $file_name_tr . $file_ext . '</small><br>';
            // ///// Ищем описание
            if (file_exists('uploads/tmp/' . $file . '.txt')) {
                $output .= '<small> - Описание найдено.</small><br>';

                $arr = file ('uploads/tmp/' . $file . '.txt');
                foreach($arr as $i => $a) $description .= $a . "\n";
                $encd = detect_encoding($description);

                if ($encd != "windows-1251") {
                    $description = convert_unicode($description);
                    $output .= '<small> - Описание перекодировано и добавлено.</small><br>';
                } else {
                    $description = $description;
                    $output .= '<small> - Описание добавлено.</small><br>';
                } 
                @unlink(ROOT_DIR . '/uploads/tmp/' . $file . '.txt');
            } else {
                $output .= '<small> - Описание не найдено.</small><br>';
            } 
            // ///////////////// Ищем скриншот
            if (file_exists('uploads/tmp/' . $file . '.png')) $screen_ext = '.png';
            if (file_exists('uploads/tmp/' . $file . '.jpg')) $screen_ext = '.jpg';
            if (file_exists('uploads/tmp/' . $file . '.gif')) $screen_ext = '.gif';

            if ($screen_ext != "") {
                $output .= '<small> - Скриншот в формате ' . $screen_ext . ' найден.</small><br>';

                if (rename('uploads/tmp/' . $file . $screen_ext, 'uploads/downloads/screen/' . $rand . $file_name_tr . $screen_ext)) {
                    $output .= '<small> - Скриншот успешно загружен как ' . $rand . $file_name_tr . $screen_ext . '</small><br>';
                    // //////// Создаем уменьшенную копию скриншота
                    $thumb = new thumbnail ('uploads/downloads/screen/' . $rand . $file_name_tr . $screen_ext);
                    if ($thumb->size_auto ($filesConfig['widththumb'])) {
                        if ($filesConfig ['allow_watermark'] == "1") $thumb->insert_watermark ($config ['max_watermark']);
                        $thumb->save ('uploads/downloads/screen_small/' . $rand . $file_name_tr . $screen_ext);
                    } 

                    if ($filesConfig ['allow_watermark'] == "1" or $config ['max_up_side']) {
                        $thumb = new thumbnail ('uploads/downloads/screen/' . $rand . $file_name_tr . $screen_ext);
                        if ($config ['max_up_side']) $thumb->size_auto ($config ['max_up_side']);
                        if ($filesConfig ['allow_watermark'] == "1") $thumb->insert_watermark ($config ['max_watermark']);
                        $thumb->save ('uploads/downloads/screen/' . $rand . $file_name_tr . $screen_ext);
                    } 

                    $file_screen = $rand . $file_name_tr . $screen_ext;
                } else {
                    $output .= '<small> - Ошибка при загрузке скриншота.</small><br>';
                } 
            } else {
                $output .= '<small> - Скриншот к файлу не найден.</small><br>';
            } 
            // /////////// Запрос на добавление файла
            $db->query("INSERT INTO downloads_files SET category='$category', title='{$file_title}', date='$_TIME', author='{$user_id['name']}', approve='1', file='" . $rand . $file_name_tr . $file_ext . "', screen='$file_screen', description='{$description}'");
            unset($file_title);
            unset($file_name_tr);
            unset($file_name);
            unset($file_ext);
            unset($rand);
            unset($file);
            unset($screen_ext);
            unset($description);
            unset($file_screen); 
            // /////// Если главный файл не переместился
        } else {
            $output .= 'Ошибка при загрузке файла <b>' . $str['name'] . '</b><br>';
        } 

        $output .= '<br>';
    } 
} 

$db->free();

if ($config['cache'] == '1') {
    @clear_cache();
} 

$db->query('DELETE FROM tmp_files');
$db->free();
$tpl->copy_tpl = '<div class="text">'.$output . '</div><div class="block"><a href="' . $PHP_SELF . '?do=admin&act=downloads&act_2=files_add_mass">Вернуться назад</a></div>';

$tpl->compile('content');
$tpl->clear();

?>