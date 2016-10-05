<div class="messagesWrapper alert alert-success">
	<button class="close" data-dismiss="alert" type="button">
		<i class="icon-remove"></i>
	</button>
	<ul class="list-unstyled">
		{foreach from=$messages item=message}
			<li>
				<span class="message-text">{$message}</span>
			</li>
		{/foreach}
	</ul>
</div>
