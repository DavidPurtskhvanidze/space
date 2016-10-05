<h1 class="tellFriendHeader"><span class="tellFriendHeader">[[Recommend listing #]]{$REQUEST.listing_id}&nbsp;</span><span class="tellFriendHeader">{$REQUEST.listing_title}</span></h1>
<div class="clearBoth"></div>
{if $message_sent}
	<p class="success">
		<span>[[Your message was sent.]]</span>
	</p>
{elseif !empty($ERRORS)}
	{include file="errors.tpl"}
{else}
	{display_error_messages}
	<p>[[Fields marked with (<span class="asterisk">*</span>) are mandatory]]</p>
	<form action="" method="post">
		<table class="form tellFriendForm">
			<tr>
				<td>[[$form_fields.name.caption]]</td>
				<td>{if $form_fields.name.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</td>
				<td>{input property="name"}</td>
			</tr>
			<tr>
				<td>[[$form_fields.friend_name.caption]]</td>
				<td>{if $form_fields.friend_name.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</td>
				<td>{input property="friend_name"}</td>
			</tr>
			<tr>
				<td>[[$form_fields.friend_email.caption]]</td>
				<td>{if $form_fields.friend_email.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</td>
				<td>{input property="friend_email"}</td>
			</tr>
			<tr>
				<td>[[$form_fields.comment.caption]]</td>
				<td>{if $form_fields.comment.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</td>
				<td>{input property="comment" parameters=['height'=>'210px']}</td>
			</tr>
			{if isset($form_fields.captcha)}
				<tr>
					<td>[[$form_fields.captcha.caption]]</td>
					<td>{if $form_fields.captcha.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</td>
					<td>{input property="captcha"}</td>
				</tr>
			{/if}
			<tr>
				<td colspan="2">
					<input type="hidden" name="action" value="send_message" />
					<input type="hidden" name="listing_id" value="{$REQUEST.listing_id}" />
					<input type="submit" value="[[Send:raw]]" class="button" />
				</td>
			</tr>
		</table>
	</form>
{/if}
{require component="jquery" file="jquery.js"}
