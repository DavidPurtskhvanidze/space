{display_success_messages}
{display_error_messages}

{if !$password_was_changed}
<form method="post" action="">
    {CSRF_token}
    <table class="changePasswordForm">
        <tr>
            <td>[[FormFieldCaptions!Password]]:</td>
            <td><input type="password" name="password" class="text form-control" /></td>
        </tr>
        <tr>
            <td>[[FormFieldCaptions!Confirm password]]:</td>
            <td><input type="password" name="confirm_password" class="text form-control" /></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <input type="hidden" name="username" value="{$username}" />
                <input type="hidden" name="verification_key" value="{$verification_key}" />
                <input type="submit" name="submit" value="[[Submit:raw]]" class="button" />
            </td>
        </tr>
    </table>
</form>
{/if}
