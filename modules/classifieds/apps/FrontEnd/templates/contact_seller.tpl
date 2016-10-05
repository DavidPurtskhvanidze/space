<div class="contactSellerFormPage">
	{if $message_sent}
        <div class="text-center">
            <img src="{url file="main^loader.gif"}" alt=""/>
        </div>
		<script type="text/javascript">
			if (location.search == '')
				location.href = location.href.split('#')[0] + '?restoreActiveTab=restore';
			else
			{
				var search = location.search;
				if(search.indexOf('restoreActiveTab') >= 0)
				{
					location.href = location.href.split('#')[0];
				}
				else
				{
					location.href = location.href.split('#')[0] + '&restoreActiveTab=restore';
				}
			}
		</script>
	{elseif !empty($ERRORS)}
	{include file="errors.tpl"}
	{else}
	{display_error_messages}
	<p>[[Fields marked with (<span class="asterisk">*</span>) are mandatory]]</p>
	<form action="" method="post" >
		<table class="form contactSellerForm">
			<tr>
				<td>[[$form_fields.FullName.caption]]</td>
				<td>{if $form_fields.FullName.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</td>
				<td>{input property="FullName"}</td>
			</tr>
			<tr>
				<td>[[$form_fields.Email.caption]]</td>
				<td>{if $form_fields.Email.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</td>
				<td>{input property="Email"}</td>
			</tr>
			<tr>
				<td>[[$form_fields.Request.caption]]</td>
				<td>{if $form_fields.Request.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</td>
				<td>{input property="Request" parameters=["height"=>"200px"]}</td>
			</tr>
			{if isset($form_fields.captcha)}
			<tr>
				<td>[[$form_fields.captcha.caption]]</td>
				<td>{if $form_fields.captcha.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</td>
				<td>{input property="captcha"}</td>
			</tr>
			{/if}
			<tr>
				<td>&nbsp;<td>
				<td>
					<input type="hidden" name="action" value="send_message" />
					<input type="hidden" name="listing_id" value="{$REQUEST.listing_id}" />
					<input type="submit" value="[[Send:raw]]" class="button" />
				</td>
			</tr>
		</table>
	</form>
	{/if}
	{require component="jquery" file="jquery.js"}
</div>
