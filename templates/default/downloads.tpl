[cat_navigation]
<div class="block">{navigation}</div>
[/cat_navigation]


[category]
<div class="link">[cat_link]{category} <em>{count}</em>[/cat_link]</div>
[/category]

[file_list]
<div class="post">
<div class="i">[screen]{screen}[/screen]</div>
<div class="cont">
<span class="user"><em>{date}</em> [title_link]<img src="../img.php?pic=ball" alt=""> <b>{title}</b>[/title_link]</span>
<div class="text">{description}</div>
</div>
</div>
[/file_list]


[file_info]
<div class="block">Файл расположен в категории: <b>[cat_link]{cat_title}[/cat_link]</b></div>

<div class="post">
<div class="i">[screen]{screen}[/screen]</div>
<div class="cont">
<span class="user"><em>{date}</em> <b>{title}</b></span>
<div class="text">{description}</div>
</div>
</div>
<div class="block">[title]Скачать[/title] <em>({file_count})</em></div>
[related_files]<div class="otvet">Похожие файлы:<br>{related_files}</div>[/related_files]<br/>
<div class="block"><img src="/templates/default/images/comm.png"/> {comm_count} <em>Комментарии</em></div>
[/file_info]