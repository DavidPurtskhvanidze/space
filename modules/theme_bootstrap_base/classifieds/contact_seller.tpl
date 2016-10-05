{require component="jquery" file="jquery.js"}
<div class="contactSellerFormPage" xmlns="http://www.w3.org/1999/html">
	{if !empty($ERRORS)}
	    {include file="errors.tpl"}
	{else}
        {display_error_messages}
        <div class="alert alert-warning text-center">[[Fields marked with (<span class="asterisk">*</span>) are mandatory]]</div>
        <form action="" method="post">
            <div class="form-group">
                <div class="input-group">
                    {input property=FullName placeholder="{$form_fields.FullName.caption}"}
                    <span class="input-group-addon">
                        {if $form_fields.FullName.is_required}<span class="asterisk">*</span>{/if}
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    {input property=Email placeholder="{$form_fields.Email.caption}"}
                    <span class="input-group-addon">
                        {if $form_fields.Email.is_required}<span class="asterisk">*</span>{/if}
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    {input property=Request placeholder="{$form_fields.Request.caption}"}
                    <span class="input-group-addon">
                        {if $form_fields.Request.is_required}<span class="asterisk">*</span>{/if}
                    </span>
                </div>
            </div>
            {if isset($form_fields.captcha)}
            <div class="form-group">
                <div class="input-group captcha">
                    {input property=captcha placeholder="{$form_fields.captcha.caption}"}
                    <span class="input-group-addon">
                        {if $form_fields.captcha.is_required}<span class="asterisk">*</span>{/if}
                    </span>
                </div>
            </div>
            {/if}
            <div class="form-group">
                <input type="hidden" name="action" value="send_message" />
                <input type="hidden" name="listing_id" value="{$REQUEST.listing_id}" />
                <button type="submit" class="btn btn-default" value="[[Send:raw]]">[[Send:raw]]</button>
            </div>
        </form>

        {if $message_sent}
            <script type="text/javascript">
                window.showSpinner();
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
        {else}
            <script type="text/javascript">
                $(document).ready(function() {
                    $('.modal').css('z-index', '2000');
                    $.loader.close(true);
                })
            </script>
        {/if}
    {/if}
</div>
{require component="jquery" file="jquery.js"}
<script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>
<script type="text/javascript">
    window.showSpinner = function()
    {
        $('.modal').css('z-index', '1');
        $.loader.open();
    }

    $(document).ready(function(){
        $('.contactSellerFormPage button[type="submit"]').click(function(){
            window.showSpinner();
        }
    );
    });
</script>
