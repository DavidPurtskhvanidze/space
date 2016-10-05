<div class="registrationPage">
	<h1>[[Registration]]</h1>
	{display_error_messages}
	<p>[[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]</p>

	<form method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
		{foreach from=$form_fields item=form_field}
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">
					[[$form_field.caption]] {if $form_field.is_required}<span class="asterisk">*</span>{/if}
				</label>

				<div class="col-sm-10">
					{input property=$form_field.id}
				</div>
			</div>
		{/foreach}

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">

				<input type="hidden" name="action" value="register"/>
				<input type="hidden" name="user_group_id" value="{$user_group_id}"/>

				<button type="submit" class="btn btn-default">[[Register:raw]]</button>
			</div>
		</div>
	</form>

	{require component="jquery" file="jquery.js"}
	{require component="jquery-maxlength" file="jquery.maxlength.js"}
	{require component="js" file="script.maxlength.js"}
    <script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>

</div>
