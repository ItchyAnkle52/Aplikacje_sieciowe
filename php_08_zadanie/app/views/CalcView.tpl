{% extends "main.tpl" %}

{% block content %}
<div class="container medium">
	<form action="{{conf.action_root}}calcCompute#fourth" method="post">
		<label for="id_kwota">Kwota: </label>
		<input id="id_kwota" type="text" name="kwota" value="{{form.kwota}}" />
		<label for="id_lata">Lata: </label>
		<input id="id_lata" type="text" name="lata" value="{{form.lata}}" />
		<label for="id_oprocentowanie">Oprocentowanie (%): </label>
		<input id="id_oprocentowanie" type="text" name="oprocentowanie" value="{{form.oprocentowanie}}" /><br />
		<input type="submit" value="Oblicz" style="display:block; margin : 0 auto;" />
	</form><br />
	<span style="float:top; margin: auto;">użytkownik: {{user.login}}, rola: {{user.role}}</span>	
	<a href="{{conf.action_url}}logout" style="display:block; margin : 0 auto; text-align: center;">wyloguj</a>
	<a href="{{conf.action_url}}resultList#fourth"  style="display:block; margin : 0 auto; text-align: center;">lista wynikow</a>
</div>	
<div class="container medium2">

{% include 'messages.tpl' %}	
{% if result.res is defined %}
<h1>Miesięczna rata wynosi: 
		{{result.res}} zł</h1>
{% endif %}
</div>
{% endblock %}