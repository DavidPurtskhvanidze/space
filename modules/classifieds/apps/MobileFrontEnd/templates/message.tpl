{foreach from=$messages item="alert_message" key="message"}
{if is_int($message)}{assign var="message" value=$alert_message}{/if}
	{if $message eq "COMMENT_ADDED_PUBLISHED"}
		<p class="success">
			<span>[[Thank you, your comment was added successfully.]]</span>
		</p>
	{elseif $message eq "COMMENT_ADDED_WAITING"}
		<p class="success">
			<span>[[Thank you, your comment was added successfully. Once the website admin approves it, it will be visible to everybody.]]</span>
		</p>
	{else}
		<p class="success">
			<span>{$message}</span>
		</p>
	{/if}
{/foreach}
