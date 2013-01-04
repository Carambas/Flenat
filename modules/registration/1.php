<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

if ($config['registr'] == 0) {
    info('Регистрация закрыта', 'К сожаления регистрация на сайте приостановлена. ');
} else {
    $_SESSION['sec_key_reg_1'] = rv();

    if (isset($_SESSION[sec_key_reg_2])) unset ($_SESSION[sec_key_reg_2]);

    $tpl->load_tpl('registration.tpl');
    $tpl->set_block("'\\[good\\](.*?)\\[/good\\]'si", "");
    $tpl->set ('[form]', '<form  method="post" action="' . $PHP_SELF . '?do=registration&act=2&registration_key=' . $_SESSION['sec_key_reg_1'] . '">');
    $tpl->set ('{code}', '<img src="' . $config['home_url'] . 'img.php" alt="sec_code">');
    $tpl->set ('[/form]', '</form>');
    $tpl->compile('content');
    $tpl->clear();
} 

?>