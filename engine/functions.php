<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

function move($go)
{
	global $db, $tpl;
	header('Location: '.$go);
	$tpl->all_clear ();
	$db->close ();
	exit;
}
function do_upload($upload_dir, $file_name, $file_is, $rand)
{
    global $config;

    if ($file_is != 1) {
        $temp_name = $_FILES ['file_file'] ['tmp_name'];
    } else {
        $temp_name = $_FILES ['file_screen'] ['tmp_name'];
    } 

    $file_name = $rand . '_' . $file_name;
    $file_path = $upload_dir . $file_name;

    if (move_uploaded_file ($temp_name, $file_path) == false) {
        echo "<center>Невозможно закачать файл. <br></center>";
        exit ();
    } 

    @chmod ($file_path, 0666);

    return $file_name;
}
// ////////////////////////////////////////
// ///// Функция отображения времени
function iTime($time) {
	$timep = date("j M", $time);
	$time_p[0]=date("j n Y", $time);
	$time_p[1]=date("G:i", $time);
	if ($time_p[0]==date("j n Y"))$timep=date("G:i", $time);
	if ($time_p[0]==date("j n Y", time()-60*60*24))$timep="вчера";
	$timep=str_replace("Jan","янв",$timep);
	$timep=str_replace("Feb","фев",$timep);
	$timep=str_replace("Mar","марта",$timep);
	$timep=str_replace("May","мая",$timep);
	$timep=str_replace("Apr","Апр",$timep);
	$timep=str_replace("Jun","июня",$timep);
	$timep=str_replace("Jul","июля",$timep);
	$timep=str_replace("Aug","авг",$timep);
	$timep=str_replace("Sep","сент",$timep);
	$timep=str_replace("Oct","окт",$timep);
	$timep=str_replace("Nov","ноября",$timep);
	$timep=str_replace("Dec","дек",$timep);
	return $timep;
} 
function online($user)
{
	global $db,$config;
	$result = $db->super_query("SELECT COUNT(*) as count FROM online WHERE uname = '{$user}' LIMIT 1");
	if ($result['count'] == 1) return '<img src="'.$config['home_url'].'img.php?pic=ball" alt="online" />';
	else return '';
}
// ////////////////////////////////////////
// ///// Функция отображения выбора категории загрузки
function downloads_cat_select($categoryid = 0, $parentid = 0, $nocat = true, $sublevelmarker = '', $returnstring = '')
{
    global $cat_info, $user_group, $member_id, $category_id;

    $root_category = array ();

    if ($parentid == 0) {
        if ($nocat) $returnstring .= '<option value="0"></option>';
    } else {
        $sublevelmarker .= '---';
    } 

    if (count($cat_info)) {
        foreach ($cat_info as $cats) {
            if ($cats['id_parent'] == $parentid) $root_category[] = $cats['id'];
        } 

        if (count($root_category)) {
            foreach ($root_category as $id) {
                $returnstring .= "<option value=\"" . $id . '"';
                if ($category_id == $id and $category_id != "") $returnstring .= ' SELECTED';
                $returnstring .= '>' . $sublevelmarker . $cat_info[$id]['name'] . "</option>\n";

                $returnstring = downloads_cat_select($categoryid, $id, $nocat, $sublevelmarker, $returnstring);
            } 
        } 
    } 
    return $returnstring;
} 
// ////////////////////////////////////////
// ///// Получение имени категории
function name_cat($id, $name = '')
{
    global $db;

    $row = $db->super_query("SELECT id_parent, name FROM downloads_category where id = '{$id}'");

    if ($row['id_parent'] != 0) {
        $row_2 = $db->super_query("SELECT id_parent, name FROM downloads_category where id = '{$row['id_parent']}'"); 
        // $name = '<a href="' . $PHP_SELF . '?do=downloads&category=' . $row_2['id_parent'] . '">Категории</a> / <a href="' . $PHP_SELF . '?do=downloads&category=' . $row['id_parent'] . '">&#8593;</a> / ' . $row_2['name'] . ' / ' . $row['name'];
        $name = '<a href="' . $PHP_SELF . '?do=downloads">Категории</a> / <a href="' . $PHP_SELF . '?do=downloads&category=' . $row['id_parent'] . '">&#8593;</a> / ' . $row_2['name'] . ' / ' . $row['name'];
        name_cat($row_2['id'], $name);
    } else {
        if ($row['name']) {
            $name = '<a href="' . $PHP_SELF . '?do=downloads&category=' . $row['id_parent'] . '">Категории</a> / ' . $row['name'];
        } else {
            $name = '<a href="' . $PHP_SELF . '?do=downloads">Категории</a>';
        } 
    } 

    return $name;
} 
// ////////////////////////////////////////
// ///// Генерация времени
function makestime($string)
{
    $day = floor($string / 86400);
    $hours = floor(($string / 3600) - $day * 24);
    $min = floor(($string - $hours * 3600 - $day * 86400) / 60);
    $sec = $string - ($min * 60 + $hours * 3600 + $day * 86400);

    return sprintf("%01d дн. %02d:%02d<small>:%02d</small>", $day, $hours, $min, $sec);
} 
// ////////////////////////////////////////
// ///// Очистка главного файла
function check_index($data)
{
    function url_replace_check($url)
    {
        $url = str_replace('&', '&amp;', $url);
        return '<a' . $url[1] . '>';
    } 
    // ////////////////////////////////////////
    // ///// Очистка форм
    function form_replace_check($form)
    {
        $form = str_replace('&', '&amp;', $form);
        return '<form' . $form[1] . '>';
    } 
    $data = preg_replace("'([\r\n])[\s]+'", "\n", $data);
    return $data;
} 
// ////////////////////////////////////////
// ///// Полная очистка строки
function check_full($data)
{
    global $gav;
    $data = str_replace("|", "I", $data);
    $data = str_replace("||", "I", $data);
    $data = str_replace("&", "", $data);
    $data = str_replace("\"", "", $data);
    $data = str_replace(">", "", $data);
    $data = str_replace("<", "", $data);
    $data = str_replace("'", "", $data);
    $data = str_replace("\"", "", $data);
    $data = str_replace("/\\\$/", "", $data);
    $data = str_replace("$", "", $data);
    $data = str_replace("\\", "", $data);
    if (!$gav) $data = str_replace("@", "", $data);
    $data = str_replace("`", "", $data);
    $data = str_replace("%", "", $data);
    $data = str_replace("^", "", $data);
    $data = addslashes($data);
    $data = htmlspecialchars($data);
    $data = stripslashes(trim($data));
    return $data;
} 
// ////////////////////////////////////////
// ///// Очистка строки с экранированием
function check($data)
{
    $data = htmlspecialchars($data);
    $data = str_replace("|", "I", $data);
    $data = str_replace("||", "I", $data);
    $data = str_replace("'", "&#39;", $data);
    $data = str_replace("\"", "&#34;", $data);
    $data = str_replace("/\\\$/", "&#36;", $data);
    $data = str_replace("$", "&#36;", $data);
    $data = str_replace("\\", "&#92;", $data);
    $data = str_replace("@", "&#64;", $data);
    $data = str_replace("`", "", $data);
    $data = str_replace("^", "&#94;", $data);
    $data = str_replace("%", "&#37;", $data);
    $data = str_replace(":", "&#58;", $data);
    $data = preg_replace("/&#58;/", ":", $data, 3);
    $data = stripslashes(trim($data));
    return $data;
} 
// ////////////////////////////////////////
// ///// КЭШ массив создание
function set_vars($file, $data)
{
    $fp = fopen(ROOT_DIR . '/cache/' . $file . '.php', 'wb+');
    fwrite($fp, serialize($data));
    fclose($fp);

    @chmod(ROOT_DIR . '/cache/' . $file . '.php', 0666);
} 
// ////////////////////////////////////////
// ///// КЭШ массив получение
function get_vars($file)
{
    return unserialize(@file_get_contents(ROOT_DIR . '/cache/' . $file . '.php'));
} 
// ////////////////////////////////////////
// ///// Получение размера файла по урл
function filesize_url($url)
{
    return ($data = @file_get_contents($url)) ? strlen($data) : false;
} 
// ////////////////////////////////////////
// ///// КЭШ создание
function create_cache($prefix, $cache_text, $cache_id = false, $member_prefix = false)
{
    global $config, $login, $user_id;

    if ($config['cache'] != "1") return false;

    if ($login) $end_file = $user_id['user_group'];
    else $end_file = "0";

    if (! $cache_id) {
        $filename = ROOT_DIR . '/cache/' . $prefix . '.tmp';
    } else {
        $cache_id = totranslit($cache_id);

        if ($member_prefix) $filename = ROOT_DIR . "/cache/" . $prefix . "_" . $cache_id . "_" . $end_file . ".tmp";
        else $filename = ROOT_DIR . "/cache/" . $prefix . "_" . $cache_id . ".tmp";
    } 

    $fp = fopen($filename, 'wb+');
    fwrite($fp, $cache_text);
    fclose($fp);

    @chmod($filename, 0666);
} 
// ////////////////////////////////////////
// ///// КЭШ получение
function cache($prefix, $cache_id = false, $member_prefix = false)
{
    if (! $cache_id) {
        $filename = ROOT_DIR . '/cache/' . $prefix . '.tmp';
    } else {
        $cache_id = totranslit($cache_id);

        if ($member_prefix) $filename = ROOT_DIR . "/cache/" . $prefix . "_" . $cache_id . "_" . $end_file . ".tmp";
        else $filename = ROOT_DIR . "/cache/" . $prefix . "_" . $cache_id . ".tmp";
    } 

    return @file_get_contents($filename);
} 
// ////////////////////////////////////////
// ///// КЭШ очистка
function clear_cache($cache_area = false)
{
    $fdir = opendir(ROOT_DIR . '/cache');

    while ($file = readdir($fdir)) {
        if ($file != '.' and $file != '..' and $file != '.htaccess' and $file != 'system') {
            if ($cache_area) {
                if (strpos($file, $cache_area) !== false) @unlink(ROOT_DIR . '/cache/' . $file);
            } else {
                @unlink(ROOT_DIR . '/cache/' . $file);
            } 
        } 
    } 
} 
// ////////////////////////////////////////
// ///// Размер базы
function mysql_size()
{
    global $db;

    $db->query("SHOW TABLE STATUS FROM `" . DBNAME . "`");
    $mysql_size = 0;
    while ($r = $db->get_array()) {
        $mysql_size += $r['Data_length'] + $r['Index_length'];
    } 
    $db->free();
    return $mysql_size;
} 
// ////////////////////////////////////////
// ///// Размер директории
function dirsize($directory)
{
    if (! is_dir($directory)) return - 1;

    $size = 0;

    if ($DIR = opendir($directory)) {
        while (($dirfile = readdir($DIR)) !== false) {
            if (@is_link($directory . '/' . $dirfile) || $dirfile == '.' || $dirfile == '..') continue;

            if (@is_file($directory . '/' . $dirfile)) $size += filesize($directory . '/' . $dirfile);

            else if (@is_dir($directory . '/' . $dirfile)) {
                $dirSize = dirsize($directory . '/' . $dirfile);
                if ($dirSize >= 0) $size += $dirSize;
                else return - 1;
            } 
        } 

        closedir($DIR);
    } 

    return $size;
} 
// ////////////////////////////////////////
// ///// Размер файла
function formatsize($file_size)
{
    if ($file_size >= 1073741824) {
        $file_size = round($file_size / 1073741824 * 100) / 100 . " Gb";
    } elseif ($file_size >= 1048576) {
        $file_size = round($file_size / 1048576 * 100) / 100 . " Mb";
    } elseif ($file_size >= 1024) {
        $file_size = round($file_size / 1024 * 100) / 100 . " Kb";
    } else {
        $file_size = $file_size . " b";
    } 
    return $file_size;
} 
// ////////////////////////////////////////
// ///// Транслит
function totranslit($var, $lower = true, $punkt = true)
{
    $NpjLettersFrom = "абвгдезиклмнопрстуфцыі";
    $NpjLettersTo = "abvgdeziklmnoprstufcyi";
    $NpjBiLetters = array ("й" => "j", "ё" => "yo", "ж" => "zh", "х" => "x", "ч" => "ch", "ш" => "sh", "щ" => "shh", "э" => "ye", "ю" => "yu", "я" => "ya", "ъ" => "", "ь" => "", "ї" => "yi", "є" => "ye");

    $NpjCaps = "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЪЫЭЮЯЇЄІ";
    $NpjSmall = "абвгдеёжзийклмнопрстуфхцчшщьъыэюяїєі";

    $var = str_replace(".php", "", $var);
    $var = trim(strip_tags($var));
    $var = preg_replace("/\s+/ms", "-", $var);
    $var = strtr($var, $NpjCaps, $NpjSmall);
    $var = strtr($var, $NpjLettersFrom, $NpjLettersTo);
    $var = strtr($var, $NpjBiLetters);

    if ($punkt) $var = preg_replace("/[^a-z0-9\_\-.]+/mi", "", $var);
    else $var = preg_replace("/[^a-z0-9\_\-]+/mi", "", $var);

    $var = preg_replace('#[\-]+#i', '-', $var);

    if ($lower) $var = strtolower($var);

    if (strlen($var) > 50) {
        $var = substr($var, 0, 50);

        if (($temp_max = strrpos($var, '-'))) $var = substr($var, 0, $temp_max);
    } 

    return $var;
} 
// ////////////////////////////////////////
// ///// Функция антифлуда
function flooder($ip)
{
    global $config, $db;

    $this_time = time() - $config['flood_time'];
    $db->query("DELETE FROM flood where id < '$this_time'");

    $sql_flood = "SELECT * FROM flood WHERE ip = '$ip'";

    if ($db->num_rows($db->query($sql_flood)) > 0) {
        $db->free();
        return true;
    } else {
        $db->free();
        return false;
    } 
} 
// ////////////////////////////////////////
// ///// Определение ИП
function check_ip($ips)
{
    $_IP = $_SERVER['REMOTE_ADDR'];

    $blockip = false;

    if (is_array($ips)) {
        foreach ($ips as $ip_line) {
            $ip_arr = rtrim($ip_line['ip']);

            $ip_check_matches = 0;
            $db_ip_split = explode(".", $ip_arr);
            $this_ip_split = explode(".", $_IP);

            for($i_i = 0; $i_i < 4; $i_i ++) {
                if ($this_ip_split[$i_i] == $db_ip_split[$i_i] or $db_ip_split[$i_i] == '*') {
                    $ip_check_matches += 1;
                } 
            } 

            if ($ip_check_matches == 4) {
                $blockip = $ip_line['ip'];
                break;
            } 
        } 
    } 

    return $blockip;
} 
// ////////////////////////////////////////
// ///// Для ИП
function check_netz($ip1, $ip2)
{
    $ip1 = explode(".", $ip1);
    $ip2 = explode(".", $ip2);

    if ($ip1[0] != $ip2[0]) return false;
    if ($ip1[1] != $ip2[1]) return false;

    return true;
} 
// ////////////////////////////////////////
// ///// Тоже для ИП
function allowed_ip($ip_array)
{
    $ip_array = trim($ip_array);

    if ($ip_array == "") {
        return true;
    } 

    $ip_array = explode("|", $ip_array);

    $db_ip_split = explode(".", $_SERVER['REMOTE_ADDR']);

    foreach ($ip_array as $ip) {
        $ip_check_matches = 0;
        $this_ip_split = explode(".", trim($ip));

        for($i_i = 0; $i_i < 4; $i_i ++) {
            if ($this_ip_split[$i_i] == $db_ip_split[$i_i] or $this_ip_split[$i_i] == '*') {
                $ip_check_matches += 1;
            } 
        } 

        if ($ip_check_matches == 4) return true;
    } 

    return false;
} 
// ////////////////////////////////////////
// ///// Проверка запроса
function check_request()
{
    $url = html_entity_decode(urldecode($_SERVER['QUERY_STRING']));

    if ($url) {
        if ((strpos($url, '<') !== false) || (strpos($url, '>') !== false) || (strpos($url, '"') !== false) || (strpos($url, './') !== false) || (strpos($url, '../') !== false) || (strpos($url, '\'') !== false) || (strpos($url, '.php') !== false)) {
            die("Попытка взлома!");
        } 
    } 

    $url = html_entity_decode(urldecode($_SERVER['REQUEST_URI']));

    if ($url) {
        if ((strpos($url, '<') !== false) || (strpos($url, '>') !== false) || (strpos($url, '"') !== false) || (strpos($url, '\'') !== false)) {
            die("Попытка взлома!");
        } 
    } 
} 
// ////////////////////////////////////////
// ///// Очистка урл
function clean_url($url)
{
    if ($url == '') return;

    $url = str_replace("http://", "", strtolower($url));
    if (substr($url, 0, 4) == 'www.') $url = substr($url, 4);
    $url = explode('/', $url);
    $url = reset($url);
    $url = explode(':', $url);
    $url = reset($url);

    return $url;
} 
define('DOMAIN', "." . clean_url($_SERVER['HTTP_HOST']));
// ////////////////////////////////////////
// ///// Установка куки
function set_cookie($name, $value, $expires)
{
    if ($expires) {
        $expires = time() + ($expires * 86400);
    } else {
        $expires = false;
    } 

    if (PHP_VERSION < 5.2) {
        setcookie($name, $value, $expires, "/", DOMAIN . "; HttpOnly");
    } else {
        setcookie($name, $value, $expires, "/", DOMAIN, null, true);
    } 
} 
// ////////////////////////////////////////
// ///// Конвертация строки
function convert_unicode($t, $to = 'windows-1251')
{
    $to = strtolower($to);

    if ($to == 'utf-8') {
        return urldecode($t);
    } else {
        if (function_exists('iconv')) $t = iconv("UTF-8", $to . "//IGNORE", $t);
        else $t = "The library iconv is not supported by your server";
    } 

    return urldecode($t);
} 
// ////////////////////////////////////////
// ///// Функция рандома
function rv()
{
    $var = rand(111111111, 999999999);
    return $var;
} 
// ////////////////////////////////////////
// ///// Очистка всего КЭША
function clear_all ()
{
    @clear_cache();
} 
// ////////////////////////////////////////
// ///// Функция вывода
function Output()
{
    global $config, $Timer, $db, $tpl, $_DOCUMENT_DATE, $copy_engine, $engine_key;

    if ($config['gzip'] == 1) {
        $Contents = ob_get_contents();
        ob_end_clean();
        $ENCODING = 'gzip';
        header("Content-Encoding: $ENCODING");
        $Contents = gzencode($Contents, 1, FORCE_GZIP);
        echo $Contents;
        exit;
    } else {
        $Contents = ob_get_contents();
        ob_end_clean();
        echo $Contents;
        exit;
    } 
} 
// ////////////////////////////////////////
// ///// Для ББкодов
function url_replace_bb($m)
{
    if (!isset($m[3])) {
        return '<noindex><a href="' . $m[1] . '" rel="nofollow" target="_blank" >' . $m[2] . '</a></noindex>';
    } else {
        return '<noindex><a href="' . $m[3] . '" rel="nofollow" target="_blank" >' . $m[3] . '</a></noindex>';
    } 
} 
function code($a)
{
$c = strtr($a['1'], array('&lt;' => '<', '&gt;' => '>', '&amp;' => '&', '&#36;' => '$', '&quot;' => '"', '&#39;' => "'", '&#92;' => '\\', '&#37;' => '%', '<br>' => "\r\n"));
$c = highlight_string($c, true);
return '<div class="quote"><Desire '.strtr($c, array("\r\n" => '<br>','<br />'=>'<br>', '$' => '&#36;', "'" => '&#39;', '\\'=>'&#92;', '%'=>'&#37;')).'</div>';
}
function iOpis($message) { return bb_code(iBr($message)); }
function iPost($message) { return smiles(iOpis($message)); }
function iBr($message) { return str_replace("\n","<br />",$message); }
// ////////////////////////////////////////
// ///// ББ коды
function bb_code($message)
{
    global $config;

    if ($config['bbcode'] == 1) {
        $message = preg_replace('#\[big\](.*?)\[/big\]#si', '<big>\1</big>', $message);
        $message = preg_replace('#\[center\](.*?)\[/center\]#si', '<center>\1</center>', $message);
        $message = preg_replace('#\[b\](.*?)\[/b\]#si', '<b>\1</b>', $message);
        $message = preg_replace('#\[i\](.*?)\[/i\]#si', '<i>\1</i>', $message);
        $message = preg_replace('#\[u\](.*?)\[/u\]#si', '<u>\1</u>', $message);
        $message = preg_replace('#\[small\](.*?)\[/small\]#si', '<small>\1</small>', $message);
        $message = preg_replace('#\[red\](.*?)\[/red\]#si', '<font color="#C00">\1</font>', $message);
        $message = preg_replace('#\[green\](.*?)\[/green\]#si', '<font color="#6C0">\1</font>', $message);
        $message = preg_replace('#\[blue\](.*?)\[/blue\]#si', '<font color="#009">\1</font>', $message);
        $message = preg_replace('#\[yellow\](.*?)\[/yellow\]#si', '<font color="#FC3">\1</font>', $message);
        $message = preg_replace('#\[q\](.*?)\[/q\]#si', '<div class="quote">\1</div>', $message);
		$message = preg_replace_callback('#\[code\](.+)\[/code\]#si', 'code', $message);
        $message = preg_replace_callback('~\\[url=(http://.+?)\\](.+?)\\[/url\\]|(http://(www.)?[0-9a-z\.-]+\.[0-9a-z]{2,6}[0-9a-zA-Z/\?\.\~&amp;_=/%-:#]*)~', 'url_replace_bb', $message);
    } 
    return $message;
} 


// ////////////////////////////////////////
// ///// Статистика
function all_stat()
{
    global $tpl, $config, $Timer, $db, $_DOCUMENT_DATE, $do, $PHP_SELF, $user_id, $login;
    $stat_time = $Timer->stop();
    $stat_compiles = round($tpl->template_parse_time, 5);
    $stat_sql_time = round($db->MySQL_time_taken, 5);
    $stat_sql_count = $db->query_num;
    if ($config['gzip'] == 1) {
        $stat_gzip = 'On';
    } else {
        $stat_gzip = 'Off';
    } 

    $tpl_stat = new tpls ();
    $tpl_stat->dir = TEMPLATE_DIR;

    $tpl_stat->load_tpl('stats.tpl');
    if ($do == 'online') {
        $tpl_stat->set_block("'\\[online\\](.*?)\\[/online\\]'si", "");
    } else {
        if ($config['onl_here'] == 2) {
            $tpl_stat->set ('[online]', '<a href="' . $PHP_SELF . '?do=online">');
            $tpl_stat->set ('[/online]', '</a>');
        } else {
            $tpl_stat->set_block("'\\[online\\](.*?)\\[/online\\]'si", "");
        } 
    } 

    switch ($config['stats']) {
        case "0" :
            $stats_show = 0;
            break;
        case "1" :
            $stats_show = 1;
            break;
        case "2" :
            if ($login and ($user_id['user_id'] == "1")) $stats_show = 1;
            else $stats_show = 0;
            break;
    } 

    if ($stats_show == 1) {
        $tpl_stat->set('[stats]', '');
        $tpl_stat->set('[/stats]', '');
        $tpl_stat->set('{stat_time}', $stat_time);
        $tpl_stat->set('{stat_compile}', $stat_compiles);
        $tpl_stat->set('{stat_sql_time}', $stat_sql_time);
        $tpl_stat->set('{stat_sql_count}', $stat_sql_count);
        $tpl_stat->set('{stat_gzip}', $stat_gzip);
    } else {
        $tpl_stat->set_block("'\\[stats\\](.*?)\\[/stats\\]'si", "");
    } 
	$tpl_stat->set ('{version}', check_full($config['version']));

    $tpl_stat->compile('stats');
    $tpl_stat->clear();

    $tpl->result['stats'] .= $tpl_stat->result['stats'];
} 
// ////////////////////////////////////////
// ///// Вывод инфо уведомления
function info($title, $text)
{
    global $tpl;

    $tpl_info = new tpls();
    $tpl_info->dir = TEMPLATE_DIR;

    $tpl_info->load_tpl('info.tpl');

    $tpl_info->set('{text}', $text);
    $tpl_info->set('{title}', $title);

    $tpl_info->compile('info');
    $tpl_info->clear();

    $tpl->result['info'] .= $tpl_info->result['info'];
} 
// ////////////////////////////////////////
// ///// Вывод смайлов
function smiles($string)
{
    global $config;
    if ($config['smiles'] == 1) {
        $dir = opendir (ROOT_DIR . "/uploads/smiles");
        while ($file = readdir ($dir)) {
            if (preg_match ("/.gif/i", $file)) {
                $smail_file[] = str_replace(".gif", "", $file);
            } 
        } 

        closedir ($dir);
        rsort($smail_file);

        foreach($smail_file as $smail_val) {
            $string = str_replace(":$smail_val", '<img src="' . $config['home_url'] . 'uploads/smiles/' . $smail_val . '.gif" alt="">', $string);
        } 
    } 
    return $string;
} 
// ////////////////////////////////////////
// ///// Хранение списка файлов в папке
function list_files_save($dir)
{
    global $tpl, $config, $Timer, $db, $_DOCUMENT_DATE, $do, $PHP_SELF, $user_id, $login;
    $file_list = '';
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file !== '.' AND $file !== '..') {
                $current_file = "{$dir}/{$file}";
                if (is_file($current_file)) {
                    $file_ext = strtolower(substr($file, strrpos($file, '.')));

                    $ignor_files = check_full($config['ignor_files']);
                    $filesConfig ['accepted_files'] = check_full($config['good_files']);

                    $ignor_files = explode (",", $ignor_files);
                    foreach ($ignor_files as $value)
                    $ignor_files [] = "." . $value;

                    $FILE_EXTS = explode (",", $filesConfig ['accepted_files']);
                    foreach ($FILE_EXTS as $value)
                    $FILE_EXTS [] = "." . $value;

                    if (! in_array ($file_ext, $FILE_EXTS) or in_array ($file_ext, $ignor_files)) {
                    } else {
                        $file_list .= "<b>{$file}</b><br>";
                        $str_db = 'INSERT INTO tmp_files set name="' . $file . '", ext="' . $file_ext . '"';
                        $db->query($str_db);
                    } 
                } 
            } 
        } 
    } 
    return $file_list;
} 
// ////////////////////////////////////////
// ///// Определение кодировки
function detect_encoding($string)
{
    static $list = array('utf-8', 'windows-1251');

    foreach ($list as $item) {
        $sample = iconv($item, $item, $string);
        if (md5($sample) == md5($string))
            return $item;
    } 
    return null;
} 

?>