{% extends "main.tpl" %}

{% block content %}
<div class="container medium">
<form action="{{conf.action_url}}login#fourth" method="post">
	<fieldset>
        <div>
			<label for="id_login">login: </label>
			<input id="id_login" type="text" name="login"/>
		</div>
        <div>
			<label for="id_pass">pass: </label>
			<input id="id_pass" type="password" name="pass" /><br />
		</div>
		<div>
			<input type="submit" value="zaloguj">
		</div>
	</fieldset>
</form>	
</div>

<div class="container medium2">
{% include 'messages.tpl' %}
</div>

{% endblock %}
