<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
$it_title = 'Справка по смайлам';
    $its_page = intval($config['smiles_str']);
    $its_mod = 'others&act=smiles';
    $its_cstart = intval($_REQUEST['cstart']);
    navigation_up();
    $dir = opendir (ROOT_DIR . "/uploads/smiles");
    while ($file = readdir ($dir)) {
        if (preg_match("/.gif/i", $file)) {
            $a[] = $file;
        } 
    } 
    closedir ($dir);
    sort($a);
    $total = count($a);
    if ($total < $its_cstart + $its_page) {
        $end = $total;
    } else {
        $end = $its_cstart + $its_page;
    } 
    for ($i = $its_cstart; $i < $end; $i++) {
        $smkod = str_replace(".gif", "", $a[$i]);
        $tpl->copy_tpl .= '<img src="' . $config['home_url'] . 'uploads/smiles/' . $a[$i] . '" alt=""> :' . $smkod . '<br>';
    } 
    $tpl->copy_tpl .= '<br>Всего cмайлов: <b>' . intval($total) . '</b><br>';
    $tpl->compile('content');
    $tpl->clear();
    $its_all = intval($total);
    navigation_down(); 
$tpl->compile('content');
$tpl->clear();

?>