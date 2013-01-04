<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$find[] = "'\r'";
$replace[] = "";
$find[] = "'\n'";
$replace[] = "";
$save_con = $_REQUEST['save_con'];
$save_con = $save_con + $config;
$handler = fopen(ROOT_DIR . '/config.php', "w");
fwrite($handler, "<?php\n\n\$config = array (\n\n");

foreach($save_con as $name => $value) {
    $value = trim(stripslashes($value));
    $value = htmlspecialchars($value, ENT_QUOTES);
    $value = preg_replace($find, $replace, $value);
    fwrite($handler, "'{$name}' => \"{$value}\",\n\n");
} 

fwrite($handler, ");\n\n?>");
fclose($handler);

info('Сохранено', 'Настройки были успешно изменены.');

$buffer = <<<HTML
<div class="block"><a href="{$config['home_url']}index.php?do=admin&act=setting">Вернуться назад</a></div>
HTML;

?>