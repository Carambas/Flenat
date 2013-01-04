[users]
<div class="post">
<b>{name}</b><br>
[date]{date}<br>[/date]
[agent]{agent}<br>[/agent]
[ip]{ip}<br>[/ip]
</div>
[/users]

[online]
<div class="block">Пользователи <em>{users_count}</em></div>
{users}
<div class="block">Всего <em>{all}</em></div>
<div class="text">
Роботы: {robots}<br>
Пользователей: {users_count}<br>
Роботов: {robots_count}<br>
Гостей: {guests}<br>
</div>
[/online]