<div class="registrationPage">
	<h1>[[Registration]]</h1>
	{display_error_messages}
	<p>[[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]</p>
	<form method="post" action="" enctype="multipart/form-data">
		<table class="form userRegistrationForm">
			<tr>
				<td><input type="hidden" name="action" value="register" /></td>
			</tr>
			{foreach from=$form_fields item=form_field}
			<tr>
				<td>[[$form_field.caption]]</td>
				<td>{if $form_field.is_required} <span class="asterisk">*</span>{/if}</td>
				<td> {input property=$form_field.id}</td>
			</tr>
			{/foreach}
			<tr>
				<td colspan="2">&nbsp;</td>
				<td>
					<input type="hidden" name="user_group_id" value="{$user_group_id}" />
					<input type="submit" value="[[Register:raw]]" />
				</td>
			</tr>
		</table>
	</form>
	{require component="jquery" file="jquery.js"}
	{require component="jquery-maxlength" file="jquery.maxlength.js"}
	{require component="js" file="script.maxlength.js"}
</div>
