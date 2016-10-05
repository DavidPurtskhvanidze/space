{if $actionFinished}
	{literal}
	<script type="text/javascript">
		window.location = "{/literal}{page_path id='user_profile'}{literal}";
	</script>
	{/literal}
{else}
	<div class="changeUsernameFormDialog">
		{display_error_messages}
		<form action="" method="post" id="ChangeUsernameFormDialog" class="form-horizontal">
            {foreach from=$formFields item=formField}
                <div class="form-group">
                    <label class="col-sm-3 control-label">[[$formField.caption]] {if $formField.is_required} <span class="asterisk">*</span>{/if} : </label>
                    <div class="col-sm-9">{input property=$formField.id}</div>
                </div>
            {/foreach}
            <div class="text-center">
                <input type="hidden" name="action" value="save" />
                <input type="submit" value="[[Save:raw]]" class="btn btn-orange h6" />
            </div>
		</form>
	</div>
{/if}
