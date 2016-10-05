<div class="form-group">
    <label class="col-sm-5 control-label bolder">
      [[OAuth & OpenID Configuration]]
    </label>
    <div class="col-sm-8">
    </div>
</div>
{extension_point name='modules\third_party_auth_providers\lib\IThirdPartyAuthProviderSettingsBlock'}
<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Client ID for Google]]
    </label>
    <div class="col-sm-8">
        <input type="text" size=50 name="google_client_id" value="{$settings.google_client_id}" class="form-control">
    </div>
</div> 
<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Client secret for Google]]
    </label>
    <div class="col-sm-8">
        <input type="text" size=50 name="google_client_secret" value="{$settings.google_client_secret}" class="form-control">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Google app name]]
    </label>
    <div class="col-sm-8">
        <input type="text" size=50 name="google_app_name" value="{$settings.google_app_name}" class="form-control">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Consumer key for Twitter]]
    </label>
    <div class="col-sm-8">
        <input type="text" size=50 name="twitter_consumer_key" value="{$settings.twitter_consumer_key}" class="form-control">
    </div>
</div> 
<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Consumer secret for Twitter]]
    </label>
    <div class="col-sm-8">
        <input type="text" size=50 name="twitter_consumer_secret" value="{$settings.twitter_consumer_secret}" class="form-control">
    </div>
</div> 
<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Access token for Twitter]]
    </label>
    <div class="col-sm-8">
        <input type="text" size=50 name="twitter_access_token" value="{$settings.twitter_access_token}" class="form-control">
    </div>
</div> 
<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Access token secret for Twitter]]
    </label>
    <div class="col-sm-8">
        <input type="text" size=50 name="twitter_access_token_secret" value="{$settings.twitter_access_token_secret}" class="form-control">
    </div>
</div> 
<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Application ID for Facebook]]
    </label>
    <div class="col-sm-8">
        <input type="text" size=50 name="facebook_app_id" value="{$settings.facebook_app_id}" class="form-control">
    </div>
</div> 
<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Application secret for Facebook]]
    </label>
    <div class="col-sm-8">
        <input type="text" size=50 name="facebook_app_secret" value="{$settings.facebook_app_secret}" class="form-control">
    </div>
</div>
<div class="clearfix form-actions ClearBoth">
   <input type="submit" value="[[Save:raw]]" class="btn btn-default">
</div>
