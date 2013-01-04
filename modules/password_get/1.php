<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$_SESSION['sec_key_pass_1'] = rv();

if (isset($_SESSION[sec_key_pass_2])) unset ($_SESSION[sec_key_pass_2]);
if (isset($_SESSION[sec_key_pass_3])) unset ($_SESSION[sec_key_pass_3]);

$tpl->load_tpl('password_get.tpl');

$tpl->set_block("'\\[form_2\\](.*?)\\[/form_2\\]'si", "");
$tpl->set_block("'\\[form_3\\](.*?)\\[/form_3\\]'si", "");
$tpl->set_block("'\\[good\\](.*?)\\[/good\\]'si", "");
$tpl->set ('[form_1]', '<form  method="post" action="' . $PHP_SELF . '?do=password_get&act_password_get=2&password_get_key=' . $_SESSION['sec_key_pass_1'] . '">');
$tpl->set ('{code}', '<img src="' . $config['home_url'] . 'img.php" alt="sec_code">');
$tpl->set ('[/form_1]', '</form>');
$tpl->compile('content');
$tpl->clear();

?>