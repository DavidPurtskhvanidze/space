<div class="loginPage">
	<h1>[[Sign In]]</h1>
	{include file="errors.tpl" errors=$errors}
	<form action="" method="post">
		<fieldset class="formFields">
			<div class="formField formFieldusername">
				<label for="username">[[FormFieldCaptions!Username]]</label>
				<input type="text" name="username" id="username" />
			</div>
			<div class="formField formFieldpassword">
				<label for="password">[[FormFieldCaptions!Password]]</label>
				<input type="password" name="password" id="password" />
			</div>
			<div class="formField formFieldkeep">
				<label for="keep">[[Keep me signed in]]</label>
				<input type="checkbox" name="keep" id="keep" />
			</div>
		</fieldset>
		<fieldset class="formControls">
            {CSRF_token}
			<input type="hidden" name="HTTP_REFERER" value="{$HTTP_REFERER}" />
			<input type="hidden" name="QUERY_STRING" value="{$QUERY_STRING}" />
			<input type="hidden" name="action" value="login" />
			<input type="submit" value="[[Login:raw]]" class="button" />
		</fieldset>
	</form>
	{module name="third_party_login" function="display_form" queryString=$QUERY_STRING httpReferer=$HTTP_REFERER}
</div>
