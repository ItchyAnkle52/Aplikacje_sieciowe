{% extends "main.tpl" %}

{% block content %}


<div class="container">
<a href="{{conf.action_url}}calcShow#fourth">powrót</a>
<table class="default">
<thead class="default">
	<tr>
		<th>ID</th>
		<th>Kwota</th>
		<th>Procent</th>
		<th>Lata</th>
        <th>Wynik</th>
	</tr>
</thead>
<tbody class = "default">
{% for w in record %}
	<tr class = "default">
		<td>{{w.idresult}}</td>
		<td>{{w.kwota}} zł</td>
		<td>{{w.oprocentowanie}}%</td>
        <td>{{w.lata}}</td>
        <td>{{w.wynik}} zł</td>
	</tr>
{% endfor %}
</tbody>
</table>
</div>
{% endblock %}
