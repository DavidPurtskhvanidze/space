{if $actionFinished}
	{literal}
	<script type="text/javascript">
		window.location = "{/literal}{page_path id='user_profile'}{literal}";
	</script>
	{/literal}
{else}
	<div class="changeUsernameFormDialog">
		{display_error_messages}
		<form action="" method="post" id="ChangeUsernameFormDialog">
			<table class="form">
				{foreach from=$formFields item=formField}
					<tr>
						<td class="inputFormCaption">[[$formField.caption]] {if $formField.is_required} <span class="asterisk">*</span>{/if} : </td>
						<td class="inputFormValue">{input property=$formField.id}</td>
					</tr>
				{/foreach}
			</table>
			<input type="hidden" name="action" value="save" />
			<input type="submit" value="[[Save:raw]]" class="button" />
		</form>
	</div>
{/if}
