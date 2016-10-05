<div class="form-group">
    <label class="col-sm-5 control-label bolder">
      [[Email Settings Configuration]]
    </label>
    <div class="col-sm-8">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[System Email]]
    </label>
    <div class="col-sm-8">
        <input type="text" size=40 name="system_email" value="{$settings.system_email}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Notification Email]]
    </label>
    <div class="col-sm-8">
        <input type="text" size=40 name="notification_email" value="{$settings.notification_email}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Reply-To]]
    </label>
    <div class="col-sm-8">
        <input type="text" size=40 name="system_email_reply_to" value="{$settings.system_email_reply_to}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Return-Path]]
    </label>
    <div class="col-sm-8">
        <input type="text" size=40 name="system_email_return_path" value="{$settings.system_email_return_path}">
    </div>
</div>
{foreach from=$emailSettings item=emailSetting}
    <div class="form-group">
        <label class="col-sm-4 control-label">
          [[$emailSetting.caption]]
        </label>
        <div class="col-sm-8">
            <input type="text" name="{$emailSetting.name}" size="40" value="{$settings.{$emailSetting.name}}">
        </div>
    </div>
{/foreach}
<div class="form-group">
    <label class="col-sm-5 control-label bolder">
      [[Configuration of Notifications]]
    </label>
    <div class="col-sm-8">
    </div>
</div>
{foreach from=$notificationSettings item=notificationSetting}
<div class="form-group">
    <label class="col-sm-4 control-label ">
      [[$notificationSetting.caption]]
    </label>
    <div class="col-sm-8">
        <div class="checkbox">
            <input type="hidden" name="{$notificationSetting.name}" value="0">
            <label>
                <input class="ace ace-switch ace-switch-6" type="checkbox" name="{$notificationSetting.name}" value="1"{if $settings.{$notificationSetting.name}} checked{/if}>
                <span class="lbl"></span>
            </label>
        </div>
    </div>
</div>
{/foreach}
{*TODO: Remove this*}
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Balance notification threshold]]
    </label>
    <div class="col-sm-8">
        <input type="text" name="user_balance_threshold" value="{$settings.user_balance_threshold}">
    </div>
</div>
{*TODO: ticket #5041 *}
<div class="form-group">
    <label class="col-sm-4 control-label">
      [[Number of days prior to listing or subscription <br /> expiration to send out notification to users]]
    </label>
    <div class="col-sm-8">
        <input type="text" name="listing_and_subscription_notification_threshold" value="{$settings.listing_and_subscription_notification_threshold}">
    </div>
</div>
<div class="clearfix form-actions ClearBoth">
   <input type="submit" value="[[Save:raw]]" class="btn btn-default">
</div>
