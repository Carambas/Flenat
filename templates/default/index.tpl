<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">
<head>
<title>{title} / {header}</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="{description}">
<meta name="keywords" content="{keywords}">
<link rel="stylesheet" type="text/css" href="/templates/default/style.css">
</head>
<body>

<div class="head">
<a href="{home}">{header}</a>
</div>

<div class="title">{welcome}, [login]<strong>{who}</strong>
[new_message]<em> <img src="/templates/default/images/msg.png"/> +{message_new}</em>[/new_message]
[/login]</div>

<div class="sub">{title}</div>

<div class="content">

{info}

[index_news]
<div class="block">{name_news} <em>{date_news}</em></div>
<div class="text">
<div class="text">{text_news}</div>
<p><img src="/templates/default/images/comm.png"/> {comm_news} [link_news]Подробнее[/link_news]</p>
</div> 
[/index_news]

<div>{index}</div>
[on=index]
<div class="block">Модули</div>

<div class="link">[downloads]Файловый архив <em>{all_downloads}</em>[/downloads]</div>
<div class="link">[book]Гостевая книга <em>{all_book}</em>[/book]</div>
<div class="link">[members]Пользователи <em>{all_members}</em>[/members]</div>
<div class="link">[pages]Страницы <em>{all_pages}</em>[/pages]</div>
<div class="link">[news]Новости <em>{all_news}</em>[/news]</div>
[online]<div class="link"><a href="{online_link}">Онлайн <em>{online}</em></a></div>[/online]
[/on]

</div>

<div class="foot">
<center>
[off=index]<a href="{home}">На главную</a>[/off]

{stats}
</center>
</div>

</body>
</html>






