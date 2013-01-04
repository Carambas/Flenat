<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

if ($config['onl_proxy'] == 1) $config_onl_proxy_1 = 'selected';
else $config_onl_proxy_0 = 'selected';
if ($config['onl_ip'] == 1) $config_onl_ip_1 = 'selected';
else $config_onl_ip_0 = 'selected';
if ($config['onl_visit'] == 1) $config_onl_visit_1 = 'selected';
else $config_onl_visit_0 = 'selected';
if ($config['onl_agent'] == 1) $config_onl_agent_1 = 'selected';
else $config_onl_agent_0 = 'selected';

if ($config['onl_here'] == 1) $config_onl_here_1 = 'selected';
if ($config['onl_here'] == 2) $config_onl_here_2 = 'selected';
if ($config['onl_here'] == 0) $config_onl_here_0 = 'selected';

$buffer = <<<HTML
<div class="post_add">
<form  method="post" action="{$PHP_SELF}?do=admin&act=setting&act_2=save">
Время онлайна в мин.:<br>
<input id="add" type="text" name="save_con[onl_time]" value="{$config['onl_time']}"><br>
Показ прокси:<br>
<select name="save_con[onl_proxy]">
<option value="1" {$config_onl_proxy_1}>Включен</option>
<option value="0" {$config_onl_proxy_0}>Выключен</option>
</select><br>
Показ IP:<br>
<select name="save_con[onl_ip]">
<option value="1" {$config_onl_ip_1}>Включен</option>
<option value="0" {$config_onl_ip_0}>Выключен</option>
</select><br>
Последний визит:<br>
<select name="save_con[onl_visit]">
<option value="1" {$config_onl_visit_1}>Показывать</option>
<option value="0" {$config_onl_visit_0}>Не показывать</option>
</select><br>
Показ агента:<br>
<select name="save_con[onl_agent]">
<option value="1" {$config_onl_agent_1}>Показывать</option>
<option value="0" {$config_onl_agent_0}>Не показывать</option>
</select><br>
Роботов показывать:<br>
<input id="add" type="text" name="save_con[onl_limit_robots]" value="{$config['onl_limit_robots']}"><br>
Пользов. показывать:<br>
<input id="add" type="text" name="save_con[onl_limit_users]" value="{$config['onl_limit_users']}"><br>
Вывод онлайна:<br>
<select name="save_con[onl_here]">
<option value="1" {$config_onl_here_1}>На главной</option>
<option value="2" {$config_onl_here_2}>В статистике</option>
<option value="0" {$config_onl_here_0}>Не показывать</option>
</select></div>
<div class="post_add"><input type="submit" value="Сохранить"></form></div>
<div class="block"><a href="{$config['home_url']}index.php?do=admin&act=setting">Вернуться назад</a></div>
HTML;

?>