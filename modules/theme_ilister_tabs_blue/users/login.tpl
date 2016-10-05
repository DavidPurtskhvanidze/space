{require component="jquery" file="jquery.js"}
<div class="login">
	{include file="errors.tpl" errors=$errors}
	<form action="{page_path id='user_login'}" method="post">
        {CSRF_token}
		<input type="hidden" name="action" value="login" />
		<input type="hidden" name="HTTP_REFERER" value="{$HTTP_REFERER}" />
		<input type="hidden" name="QUERY_STRING" value="{$QUERY_STRING}" />
		<ul>
			<li class="formFields username">
				<input type="text" name="username" class="form-control">
			</li>
			<li class="formFields password">
				<input type="password" name="password" class="form-control">
			</li>
			<li class="formFields keep">
				<input type="checkbox" name="keep" id="keep" />
				<label for="keep">[[Keep me signed in]]</label>
			</li>
			<li class="formControls submit">
				<input type="submit" value="[[Login:raw]]" class="button" />
			</li>
		</ul>
	</form>
	<div>
		<a href="{page_path id='password_recovery'}">[[Forgot Your Password?]]</a><br />
	</div>
	{module name="third_party_login" function="display_form" queryString=$QUERY_STRING httpReferer=$HTTP_REFERER}
</div>

<script type="text/javascript">
var usernameChanged = false;
var passwordChanged = false;
var usernameFieldCaption = '[[FormFieldCaptions!Username]]';
var passwordFieldCaption = '[[FormFieldCaptions!Password]]';
{literal}
$(document).ready(function(){
	function setInputCaption(input, caption)
	{
		input.attr('value', caption);
		input.bind('focus', function(){
			if ($(this).val() == caption){
				$(this).attr('value', '');
			}
		});
		input.bind('blur', function(){
			if ($(this).val() == ''){
				$(this).attr('value', caption);
			}
		});
	}
	
	setInputCaption($('.login input[name="username"]'), usernameFieldCaption);
	setInputCaption($('.login input[name="password"]'), passwordFieldCaption);
});
</script>
{/literal}
