<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ///////////////////////////////////////////////////////////////
// //
// //
// ///////////////////////////////////////////////////////////////
$book = array ('name' => $_POST['book_name'],
    'text' => $_POST['book_text'],
    'captcha' => $_POST['book_captcha'],
    'key' => $_GET['book_key'],
    );
$book = array_map("check", $book);
$is_book = 1;

if ($is_book == 1 and $sec_code and $_SESSION['sec_code'] != $book['captcha']) {
    $error .= 'Введён неверный секретный код. ';
    $is_book++;
} 

if ($is_book == 1 and $config['book_on'] == 0) {
    $error .= 'Гостевая закрыта для добавления сообщений. ';
    $is_book++;
} 

if ($is_book == 1 and $config['book_on_user'] == 0) {
    $error .= 'Гостевая закрыта для добавления сообщений. ';
    $is_book++;
} 

if ($is_book == 1 and $_SESSION['sec_key_book_1'] != $book['key']) {
    $error .= 'Произошла ошибка сессии. ';
    $is_book++;
} 

If (! $login and $config['book_on'] == 0 and $is_book == 1) {
    $error .= 'Гостевая закрыта для гостей. ';
    $is_book++;
} 

if (! $login) {
    if ($is_book == 1 and $book['name'] == '') {
        $error .= 'Вы не ввели имя. ';
        $is_book++;
    } 

    if ($is_book == 1 and preg_match("/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\+]/", $book['name'])) {
        $error .= 'Имя состоит из недопустимых символов. ';
        $is_book++;
    } 

    if ($is_book == 1 and $book['name'] != '' and strlen($book['name']) < 4) {
        $error .= 'Имя должно быть более 4 символов. ';
        $is_book++;
    } 

    if ($is_book == 1 and $book['name'] != '' and strlen($book['name']) > 15) {
        $error .= 'Имя должно быть не более 15 символов. ';
        $is_book++;
    } 
    $u_count = $db->super_query("SELECT COUNT(*) as count FROM users where name = '{$book['name']}'");

    if ($is_book == 1 and $book['name'] != '' and $u_count['count'] > 0) {
        $error .= 'Данный логин зарегистрирован пользователем, выбирите другой. ';
        $is_book++;
    } 
} else {
    $book['name'] = $user_id['name'];
} 

if ($is_book == 1 and $book['text'] == '') {
    $error .= 'Вы не ввели текст сообщения. ';
    $is_book++;
} 

if ($is_book == 1 and $book['text'] != '' and strlen($book['text']) < 10) {
    $error .= 'Текст сообщения должен быть более 10 символов. ';
    $is_book++;
} 

if ($is_book == 1 and $book['text'] != '' and strlen($book['text']) > 1000) {
    $error .= 'Текст сообщения должен быть не более 1000 символов. ';
    $is_book++;
} 

if ($is_book == 1 and flooder($_IP) == true) {
    $error .= 'Вы слишком часто добавляете сообщения. Следующее сообщение можно добавить через ' . $config['flood_time'] . ' секунд. ';
    $is_book++;
} 
$_SESSION['sec_key_book_2'] = rv();
if (isset($_SESSION['sec_key_book_1'])) unset ($_SESSION['sec_key_book_1']);
if ($is_book != 1) {
    info('Ошибка', $error);

    $tpl->copy_tpl = '<a href="' . $PHP_SELF . '?do=book&act=index">Вернуться назад</a><br>';

    $tpl->compile('content');
    $tpl->clear();
} else {
    if ($login) {
        $registr = 1;
    } else {
        $registr = 0;
    } 
    $db->query("INSERT INTO book SET author = '{$book['name']}', text = '{$book['text']}', date = '{$_TIME}', ip = '{$_IP}', soft = '{$_SOFT}', register = '{$registr}'");
    $db->query("INSERT INTO flood (id, ip) values ('$_TIME', '$_IP')");
    $db->free();
    if ($config['cache'] == '1') {
        clear_cache('main_counters');
        clear_cache('book_count');
    } 
    @header ('Location: ' . $PHP_SELF . '?do=book&act=index');
    exit;
} 

?>