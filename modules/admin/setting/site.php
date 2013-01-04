<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$templates_list = array ();
$dir = ROOT_DIR . '/templates';
$handle = opendir($dir);

while (false !== ($file = readdir($handle))) {
    if (@is_dir("./templates/$file") and ($file != "." AND $file != "..")) {
        $templates_list[] = $file;
    } 
} 

closedir($handle);
sort($templates_list);

foreach ($templates_list as $single_template) {
    if ($skin == "$single_template") {
        $selected = " selected=\"selected\"";
    } else {
        $selected = "";
    } 
    $skin_list .= "<option value=\"$single_template\"" . $selected . ">$single_template</option>";
} 

if ($config['gzip'] == 1) $config_gzip_1 = 'selected';
else $config_gzip_0 = 'selected';
if ($config['registr'] == 1) $config_registr_1 = 'selected';
else $config_registr_0 = 'selected';
if ($config['stats'] == 1) $config_stats_1 = 'selected';
else if ($config['stats'] == 0) $config_stats_0 = 'selected';
else $config_stats_2 = 'selected';
if ($config['comm_add_news'] == 1) $config_comm_add_news_1 = 'selected';
else $config_comm_add_news_0 = 'selected';
if ($config['index_news'] == 1) $config_index_news_1 = 'selected';
else $config_index_news_0 = 'selected';
if ($config['cache'] == 1) $config_cache_1 = 'selected';
else $config_cache_0 = 'selected';
if ($config['log_hash'] == 1) $config_log_hash_1 = 'selected';
else $config_log_hash_0 = 'selected';
if ($config['cache_check_all'] == '1') $selcache_check_all1 = 'selected';
if ($config['cache_check_all'] == '2') $selcache_check_all2 = 'selected';
if ($config['cache_check_all'] == '3') $selcache_check_all3 = 'selected';
if ($config['cache_check_all'] == '4') $selcache_check_all4 = 'selected';
if ($config['cache_check_all'] == '5') $selcache_check_all5 = 'selected';
if ($config['cache_check_all'] == '6') $selcache_check_all6 = 'selected';
if ($config['cache_check_all'] == '7') $selcache_check_all7 = 'selected';
if ($config['cache_check_all'] == '8') $selcache_check_all8 = 'selected';
if ($config['cache_check_all'] == '10') $selcache_check_all10 = 'selected';
if ($config['cache_check_all'] == '14') $selcache_check_all14 = 'selected';
if ($config['captcha_out'] == '1') $config_captcha_1 = 'selected';
else $config_captcha_0 = 'selected';
if ($config['bbcode'] == '1') $config_bbcode_1 = 'selected';
else $config_bbcode_0 = 'selected';
if ($config['smiles'] == '1') $config_smiles_1 = 'selected';
else $config_smiles_0 = 'selected';

$buffer = <<<HTML
<div class="post_add">
<form  method="post" action="{$PHP_SELF}?do=admin&act=setting&act_2=save">
Адрес сайта:<br>
<input id="add" type="text" name="save_con[home_url]" value="{$config['home_url']}"><br>
Почта администратора:<br>
<input id="add" type="text" name="save_con[adm_mail]" value="{$config['adm_mail']}"><br>
Сжатие страниц:<br>
<select name="save_con[gzip]">
<option value="1" {$config_gzip_1}>Включить</option>
<option value="0" {$config_gzip_0}>Выключить</option>
</select><br>
Регистрация:<br>
<select name="save_con[registr]">
<option value="1" {$config_registr_1}>Включена</option>
<option value="0" {$config_registr_0}>Выключена</option>
</select><br>
Шаблон по умолчанию:<br>
<select name="save_con[skin]">
{$skin_list}
</select><br>
Статистика:<br>
<select name="save_con[stats]">
<option value="2" {$config_stats_2}>Включена (Для админа)</option>
<option value="1" {$config_stats_1}>Включена</option>
<option value="0" {$config_stats_0}>Выключена</option>
</select><br>
Кэширование:<br>
<select name="save_con[cache]">
<option value="1" {$config_cache_1}>Включено</option>
<option value="0" {$config_cache_0}>Выключено</option>
</select><br>
Последняя новость на главной:<br>
<select name="save_con[index_news]">
<option value="1" {$config_index_news_1}>Показывать</option>
<option value="0" {$config_index_news_0}>Не показывать</option>
</select><br>
Автоочистка кэша:<br>
<select name='save_con[cache_check_all]'>
<option value='1' {$selcache_check_all1}>Через 1 час</option>
<option value='2' {$selcache_check_all2}>Через 2 часа</option>
<option value='3' {$selcache_check_all3}>Через 3 часа</option>
<option value='4' {$selcache_check_all4}>Через 4 часа</option>
<option value='5' {$selcache_check_all5}>Через 5 часов</option>
<option value='6' {$selcache_check_all6}>Через 6 часов</option>
<option value='7' {$selcache_check_all7}>Через 7 часов</option>
<option value='8' {$selcache_check_all8}>Через 8 часов</option>
<option value='10' {$selcache_check_all10}>Через 10 часов</option>
<option value='14' {$selcache_check_all14}>Через 14 часов</option>
</select><br>
Секреный код:<br>
<select name="save_con[captcha_out]">
<option value="1" {$config_captcha_1}>Только гостям</option>
<option value="0" {$config_captcha_0}>Для всех</option>
</select><br>
Ключ авторизации:<br>
<select name="save_con[log_hash]">
<option value="1" {$config_log_hash_1}>Сбрасывать</option>
<option value="0" {$config_log_hash_0}>Не сбрасывать</option>
</select><br>
Время антифлуда в сек.:<br>
<input id="add" type="text" name="save_con[flood_time]" value="{$config['flood_time']}"></div>
<div class="post_add"><input type="submit" value="Сохранить"></form></div>
<div class="block"><a href="{$config['home_url']}index.php?do=admin&act=setting">Вернуться назад</a></div>
HTML;

?>