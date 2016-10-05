<div class="form-group">
    <label class="col-sm-4 control-label bolder">
      [[Recent Tweets Configuration]]
    </label>
    <div class="col-sm-8">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">
      
    </label>
    <div class="col-sm-8">
        {if !$twitterIsSetUp}
            <div class="help-block">
                [[Please fill in the <a class="tabControl" href="#ThirdPartyAuthorization">Twitter Application Settings</a> in order to enable Recent Tweets.]]
            </div>
        {/if}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">
        [[Screen Name]]
    </label>
    <div class="col-sm-8">
        <input type="text" size=50 name="recentTweets_screenName" value="{$settings.recentTweets_screenName}" class="form-control">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">
        [[Cache Lifetime (in minutes)]]
    </label>
    <div class="col-sm-8">
        <input type="text" size=50 name="recentTweets_lifeTime" value="{$settings.recentTweets_lifeTime}" class="form-control">
    </div>
</div>
<div class="clearfix form-actions ClearBoth">
   <input type="submit" value="[[Save:raw]]" class="btn btn-default">
</div>
