{% if messages.isError() %}
<div class="messages error">
	<ol>
	{% for err in messages.getErrors() %}
		<li>{{err}}</li>
	{% endfor %}
	</ol>
</div>
{% endif %}

{% if messages.isInfo() %}
<div class="messages info bottom-margin">
	<ol>
	{% for inf in messages.isInfo() %}
		<li>{{inf}}</li>
	{% endfor %}
	</ol>
</div>
{% endif %}
