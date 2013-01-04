<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

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

$add_file = array ('file_cat' => $_POST['file_cat'],
    'file_title' => $_POST['file_title'],
    'description' => $_POST['description'],
    );

$is_add = 1;

if ($is_add == 1 and $add_file['file_title'] == '') {
    $error .= 'Вы не ввели название файла. ';
    $is_add++;
} 

if ($is_add == 1 and preg_match("/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\+]/", $add_file['file_title'])) {
    $error .= 'Название файла состоит из недопустимых символов. ';
    $is_add++;
} 

if ($is_add == 1 and $add_file['file_title'] != '' and strlen($add_file['file_title']) < 5) {
    $error .= 'Название файла должно быть более 5 символов. ';
    $is_add++;
} 

if ($is_add == 1 and $add_file['file_title'] != '' and strlen($add_file['file_title']) > 30) {
    $error .= 'Название файла должно быть не более 30 символов. ';
    $is_add++;
} 

if ($is_add == 1 and $add_file['description'] == '') {
    $error .= 'Вы не ввели описание файла. ';
    $is_add++;
} 

if ($is_add == 1 and $add_file['description'] != '' and strlen($add_file['description']) < 10) {
    $error .= 'Описание файла должно быть более 10 символов. ';
    $is_add++;
} 

if ($is_add == 1 and $add_file['description'] != '' and strlen($add_file['description']) > 1000) {
    $error .= 'Описание файла должно быть не более 1000 символов. ';
    $is_add++;
} 

if ($add_file['file_cat'] == '0' or $add_file['file_cat'] == '') {
    $error .= 'Вы не указали категорию. ';
    $is_add++;
} else {
    $cat_app = $db->super_query("SELECT * FROM downloads_category WHERE id = '{$add_file['file_cat']}'");
    $add_file['file_cat'] = intval($add_file['file_cat']);
} 

if ($is_add == 1 and $cat_app['name'] == '') {
    $error .= 'Произошла ошибка при выборе категории. ';
    $is_add++;
} 

$ignor_files = explode (",", $ignor_files);
foreach ($ignor_files as $value)
$ignor_files [] = "." . $value;

$FILE_EXTS = explode (",", $filesConfig ['accepted_files']);
foreach ($FILE_EXTS as $value)
$FILE_EXTS [] = "." . $value;

if (! empty ($_FILES['file_file']['name'])) {
    $file_type = $_FILES['file_file']['type'];
    $file_name = $_FILES['file_file']['name'];
    $file_name_arr = explode (".", $file_name);
    $type = end ($file_name_arr);
    $file_name = totranslit (stripslashes ($file_name_arr [0])) . "." . totranslit ($type);
    $filesize1 = $_FILES ['file_file']['size'];
    $file_ext = strtolower (substr ($file_name, strrpos ($file_name, ".")));
    if (! empty ($_FILES['file_file']['name'])) {
        $filesize = $filesize1;
    } 

    if (empty ($_FILES['file_file']['name'])) {
        $filesize = $size * 1024;
        $file_name = 0;
    } elseif ($filesize1 > $MAX_SIZE) {
        $error .= 'Файл превышает максимально допустимый размер закачиваемого файла. ';
        $is_add++;
    } elseif (! in_array ($file_ext, $FILE_EXTS) or in_array ($file_ext, $ignor_files)) {
        $error .= 'Извините, но такой тип файла: <b>' . $file_name . '</b> (' . $file_type . ') не разрешён для загрузки. ';
        $is_add++;
    } 
} else {
    $error .= 'Вы не указали загружаемый файл. ';
    $is_add++;
} 

if ($add_screens == "1") {
    $FILE_EXTS2 = explode (",", $filesConfig ['accepted_files2']);
    foreach ($FILE_EXTS2 as $value)
    $FILE_EXTS2 [] = "." . $value;

    if (! empty ($_FILES['file_screen']['name'])) {
        $file_type2 = $_FILES['file_screen']['type'];
        $file_name2 = $_FILES['file_screen']['name'];
        $file_name_arr2 = explode (".", $file_name2);
        $type2 = end ($file_name_arr2);
        $file_name2 = totranslit (stripslashes ($file_name_arr2 [0])) . "." . totranslit ($type2);
        $filesize12 = $_FILES ['file_screen']['size'];
        $file_ext2 = strtolower (substr ($file_name2, strrpos ($file_name2, ".")));
        if (! empty ($_FILES['file_screen']['name'])) {
            $filesize2 = $filesize12;
        } 

        if (empty ($_FILES['file_screen']['name'])) {
            $filesize2 = $size * 1024;
            $file_name = 0;
        } elseif ($filesize12 > $MAX_SIZE2) {
            $error .= 'Скриншот превышает максимально допустимый размер закачиваемого изображения. ';
            $is_add++;
            $stop = "Code files 1";
        } elseif (! in_array ($file_ext2, $FILE_EXTS2) or in_array ($file_ext2, $ignor_files)) {
            $error .= 'Извините, но такой тип скриншота: <b>' . $file_name2 . '</b> (' . $file_type2 . ') не разрешён для загрузки. ';
            $is_add++;
        } 
    } else {
        $error .= 'Вы не выбрали файл скриншота. ';
        $is_add++;
    } 
} 

if ($is_add != 1) {
	info('Ошибка', $error);
} else {
    $rand = rv();
    if ($add_screens == "1") {
        do_upload(ROOT_DIR . '/uploads/downloads/screen/', $file_name2, 1, $rand);
    } 
    do_upload(ROOT_DIR . '/uploads/downloads/files/', $file_name, 0, $rand);

    $file_name = $rand . '_' . $file_name;
    if ($add_screens == "1") {
        $screen_name = $rand . '_' . $file_name2;
    } 

    info('Успешно', 'Файл был успешно загружен на сайт.');

    if ($add_screens == 1) {
        $thumb = new thumbnail (ROOT_DIR . '/uploads/downloads/screen/' . $screen_name);

        if ($thumb->size_auto ($filesConfig['widththumb'])) {
            $thumb->jpeg_quality ($config ['jpeg_quality']);

            if ($filesConfig ['allow_watermark'] == "1")
                $thumb->insert_watermark ($config ['max_watermark']);

            $thumb->save (ROOT_DIR . '/uploads/downloads/screen_small/' . $screen_name);
        } 

        if ($filesConfig ['allow_watermark'] == "1" or $config ['max_up_side']) {
            $thumb = new thumbnail (ROOT_DIR . '/uploads/downloads/screen/' . $screen_name);
            $thumb->jpeg_quality ($config ['jpeg_quality']);

            if ($config ['max_up_side'])
                $thumb->size_auto ($config ['max_up_side']);

            if ($filesConfig ['allow_watermark'] == "1")
                $thumb->insert_watermark ($config ['max_watermark']);

            $thumb->save (ROOT_DIR . '/uploads/downloads/screen/' . $screen_name);
        } 
    }
	
	if (intval($_REQUEST['archive'])) {
		if (function_exists ("zip_open")) {
			$file_path = ROOT_DIR . '/uploads/downloads/files/'.$file_name;
            $zip = new ZipArchive ();
            $file_ext = strtolower (substr ($file_name, strrpos ($file_name, ".")));
            if ($file_ext == ".zip") {
                $zip->open ($file_path, ZIPARCHIVE::CREATE);
                $zip->addFile (ROOT_DIR . "/" . $config['file_copyr'], $config['file_copyr']);
            } else {
                $zip->open ($file_path . ".zip", ZIPARCHIVE::CREATE);
                $zip->addFile ($file_path, $file_name);
                $zip->addFile (ROOT_DIR . "/" . $config['file_copyr'], $config['file_copyr']);
                $file_name .= ".zip";
            } 
            $zip->close ();
        	if ($file_ext != ".zip") @unlink ($file_path);
		}
	}
    if ($config['cache'] == '1') @clear_cache();

    $db->query("INSERT INTO downloads_files SET category='{$add_file['file_cat']}', title='{$add_file['file_title']}', date='$_TIME', author='{$user_id['name']}', approve='1', file='$file_name', screen='$screen_name', description='{$add_file['description']}'");
}
$tpl->copy_tpl = '<div class="block"><a href="' . $PHP_SELF . '?do=admin&act=downloads&act_2=files_add">Вернуться назад</a></div>';

$tpl->compile('content');
$tpl->clear();
?>