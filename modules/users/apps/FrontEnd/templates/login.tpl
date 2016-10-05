<div class="loginPage">
<h1>[[Sign In]]</h1>
{include file="errors.tpl" errors=$errors}
<form action="{page_path id='user_login'}" method="post">
    {CSRF_token}
	<table class="form userLoginForm">
		<tr><td colspan="2"><input type="hidden" name="action" value="login" /></td></tr>
		<tr>
			<td>[[FormFieldCaptions!Username]]</td>
			<td><input type="text" class="logInNameInput form-control" name="username" /></td>
		</tr>
		<tr>
			<td>[[FormFieldCaptions!Password]]</td>
			<td><input class="logInPassInput form-control" type="password" name="password" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="keep" />[[Keep me signed in]]</td>
			<td align="right">
				<input type="hidden" name="HTTP_REFERER" value="{$HTTP_REFERER}" />
				<input type="hidden" name="QUERY_STRING" value="{$QUERY_STRING}" />
				<input type="submit" value="[[Login:raw]]" class="button" />
			</td>
		</tr>
	</table>
</form>
<br />
<a href="{page_path id='password_recovery'}">[[Forgot Your Password?]]</a> &nbsp;|&nbsp; <a href="{page_path id='user_registration'}">[[Registration]]</a>
{module name="third_party_login" function="display_form" queryString=$QUERY_STRING httpReferer=$HTTP_REFERER}
</div>
