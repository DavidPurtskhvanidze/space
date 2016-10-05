<div class="messagesWrapper alert alert-warning">
	<button class="close" data-dismiss="alert" type="button">
		<i class="icon-remove"></i>
	</button>
	<ul>
		{foreach from=$messages item=message}
			<li>{$message}</li>
		{/foreach}
	</ul>
</div>
