[navigation]
<div class="panel">
<p>
[sort_view_all]<span class="active">Все <em>{all_members}</em></span>[/sort_view_all]
[sort_skill]<span>Популярные</span>[/sort_skill]
[sort_life]<span>Живучие</span>[/sort_life]
</p>
<table><tr>
<td width="100%">
<form method="get"><input type="hidden" value="members" name="do">
<input name="searchname" id="add" />
</td>
<td width="100%">
</td>
<td width="100%">
</td>
<td>
<input class="button" type="submit" value="Поиск"></form>
</td></tr></table>
</div>
[/navigation]


[members]
<div class="post">
<div class="i"><img src="{avatar}" /></div>
<div class="cont">
<span class="user"><strong>{usertitle}</strong></span>
<div class="text">
Зарегистрирован: {registration}<br>
Был на сайте: {lastdate}<br>
</div>
</div>
</div>
[/members]
