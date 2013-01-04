<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$it_title = 'Справка по стилям';
$tpl->copy_tpl .= <<<HTML
[center]По центру[/center] - Текст по центру<br>
[red]<font color="#C00">Красный</font>[/red] - Красный текст<br>
[green]<font color="#6C0">Зеленый</font>[/green] - Зелёный текст<br>
[blue]<font color="#009">Синий</font>[/blue] - Синий текст<br>
[yellow]<font color="#FC3">Жёлтый</font>[/yellow] - Жёлтый тест<br>
[big]<big>Большой</big>[/big] - Большой текст<br>
[b]<b>Жирный</b>[/b] - Жирный текст<br>
[i]<i>Наколнный</i>[/i] - Наклонный текст<br>
[u]<u>Подчёркнутый</u>[/u] - Подчёркнутый текст<br>
[small]<small>Маленький</small>[/small] - Маленький текст<br>
[q]Цитата[/q] - Текст цитаты<br>
[code]Код[/code] - Вставка кода<br>
[url=http://site.ru]mysite.ru[/url] - Ссылка на сайт<br><br>
HTML;
$tpl->compile('content');
$tpl->clear();
?>