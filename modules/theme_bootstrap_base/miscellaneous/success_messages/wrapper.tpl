<div class="messagesWrapper success alert alert-success alert-dismissable" role="alert">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<ul class="list-unstyled">
		{foreach from=$messages item=message}
			<li>{$message}</li>
		{/foreach}
	</ul>
</div>
