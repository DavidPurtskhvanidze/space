<div class="messagesWrapper alert alert-danger">
	<button class="close" data-dismiss="alert" type="button">
		<i class="icon-remove"></i>
	</button>
  <ul class="list-unstyled">
      {foreach from=$messages item=message}
          <li>
						<i class="icon-exclamation-sign"></i>
						<span class="message-text">{$message}</span>
					</li>
      {/foreach}
  </ul>
</div>
