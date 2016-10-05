<div class="form-group">
    <label class="col-sm-4 control-label bolder">
      [[CAPTCHA Options]]
    </label>
    <div class="col-sm-8">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[CAPTCHA on the Contact Us Form]]
    </label>
    <div class="col-sm-8">
        <div class="checkbox">
            <input type="hidden" name="captcha_in_contact_form" value="0">
            <label>
                <input class="ace ace-switch ace-switch-6" type="checkbox" name="captcha_in_contact_form" value="1"{if $settings.captcha_in_contact_form} checked{/if}>
                <span class="lbl"></span>
            </label>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[CAPTCHA on the Contact Seller Form]]
    </label>
    <div class="col-sm-8">
        <div class="checkbox">
            <input type="hidden" name="captcha_in_contact_seller_form" value="0">
            <label>
                <input class="ace ace-switch ace-switch-6" type="checkbox" name="captcha_in_contact_seller_form" value="1"{if $settings.captcha_in_contact_seller_form} checked{/if}>
                <span class="lbl"></span>
            </label>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[CAPTCHA on the Registration Form]]
    </label>
    <div class="col-sm-8">
        <div class="checkbox">
            <input type="hidden" name="captcha_in_registration_form" value="0">
            <label>
                <input class="ace ace-switch ace-switch-6" type="checkbox" name="captcha_in_registration_form" value="1"{if $settings.captcha_in_registration_form} checked{/if}>
                <span class="lbl"></span>
            </label>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[CAPTCHA on the Tell Friend Form]]
    </label>
    <div class="col-sm-8">
        <div class="checkbox">
            <input type="hidden" name="captcha_in_tell_friend_form" value="0">
            <label>
                <input class="ace ace-switch ace-switch-6" type="checkbox" name="captcha_in_tell_friend_form" value="1"{if $settings.captcha_in_tell_friend_form} checked{/if}>
                <span class="lbl"></span>
            </label>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[CAPTCHA on the Contact User Form]]
    </label>
    <div class="col-sm-8">
        <div class="checkbox">
            <input type="hidden" name="captcha_in_contact_user_form" value="0">
            <label>
                <input class="ace ace-switch ace-switch-6" type="checkbox" name="captcha_in_contact_user_form" value="1"{if $settings.captcha_in_contact_user_form} checked{/if}>
                <span class="lbl"></span>
            </label>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[CAPTCHA on the Report Inappropriate Content Page]]
    </label>
    <div class="col-sm-8">
        <div class="checkbox">
            <input type="hidden" name="captcha_in_report_improper_content_form" value="0">
            <label>
                <input class="ace ace-switch ace-switch-6" type="checkbox" name="captcha_in_report_improper_content_form" value="1"{if $settings.captcha_in_report_improper_content_form} checked{/if}>
                <span class="lbl"></span>
            </label>
        </div>
    </div>
</div>
<div class="clearfix form-actions ClearBoth">
   <input type="submit" class="btn btn-default" value="[[Save:raw]]">
</div>
