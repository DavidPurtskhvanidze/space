<h3 class="tellFriendHeader"><span class="tellFriendHeader">[[Recommend listing #]]{$REQUEST.listing_id}&nbsp;</span><span class="tellFriendHeader">{$REQUEST.listing_title}</span></h3>
{if $message_sent}
	<p class="alert alert-success">
		<span>[[Your message was sent.]]</span>
	</p>
{elseif !empty($ERRORS)}
	{include file="errors.tpl"}
{else}
	{display_error_messages}
	<div class="alert alert-warning">[[Fields marked with (<span class="asterisk">*</span>) are mandatory]]</div>
	<form action="" method="post" class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-4 control-label">[[$form_fields.name.caption]] {if $form_fields.name.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</label>
				<div class="col-sm-8">{input property="name"}</div>
			</div>
			<div class="form-group">
                <label class="col-sm-4 control-label ">[[$form_fields.friend_name.caption]] {if $form_fields.friend_name.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</label>
                <div class="col-sm-8">{input property="friend_name"}</div>
			</div>
			<div class="form-group">
                <label class="col-sm-4 control-label ">[[$form_fields.friend_email.caption]] {if $form_fields.friend_email.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</label>
                <div class="col-sm-8">{input property="friend_email"}</div>
			</div>
			<div class="form-group">
                <label class="col-sm-4 control-label ">[[$form_fields.comment.caption]] {if $form_fields.comment.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</label>
                <div class="col-sm-8">{input property="comment" parameters=['height'=>'210px']}</div>
			</div>
			{if isset($form_fields.captcha)}
				<div class="form-group">
                    <label class="col-sm-4 control-label ">[[$form_fields.captcha.caption]] {if $form_fields.captcha.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</label>
                    <div class="col-sm-8">{input property="captcha"}</div>
				</div>
			{/if}
			<div class="form-group">
				<div class="col-sm-8 col-sm-offset-4">
					<input type="hidden" name="action" value="send_message" />
					<input type="hidden" name="listing_id" value="{$REQUEST.listing_id}" />
					<input type="submit" value="[[Send:raw]]" class="btn btn-default" />
				</div>
			</div>
	</form>
{/if}
{require component="jquery" file="jquery.js"}
<script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>
