{capture assign="opendIdAndOAuthSettingsLink"}#ThirdPartyAuthorization{/capture}
<div class="form-group">
    <label class="col-sm-3 control-label bolder">
      [[Listing & News Repost]]
    </label>
    <div class="col-sm-8">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Twitter repost is {if $twitterStatus}enabled{else}disabled{/if}.]]
    </label>
    <div class="col-sm-8">
        {if $twitterIsSetUp}
            {if $twitterStatus}
                <a href="{page_path module='users' function='listing_repost_settings'}?provider=Twitter&action=disable">[[Disable]]</a>
            {else}
                <a href="{page_path module='users' function='listing_repost_settings'}?provider=Twitter&action=enable">[[Enable]]</a>
            {/if}
        {else}
            [[In order to enable Twitter repost please fill in the Twitter <a class="tabControl" href="#ThirdPartyAuthorization">application settings</a>.]]
        {/if}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Facebook repost is {if $facebookStatus}enabled{else}disabled{/if}.]]
    </label>
    <div class="col-sm-8">
        {if $facebookIsSetUp}
            {if $facebookStatus}
                <a href="{page_path module='users' function='listing_repost_settings'}?provider=Facebook&action=disable">[[Disable]]</a>
                | <a href="{page_path module='users' function='listing_repost_settings'}?provider=Facebook&action=refresh">[[Refresh]]</a>
            {else}
                <a href="{page_path module='users' function='listing_repost_settings'}?provider=Facebook&action=enable">[[Enable]]</a>
            {/if}
        {else}
            [[In order to enable Facebook repost please fill in the Facebook <a class="tabControl" href="#ThirdPartyAuthorization">application settings</a>.]]
        {/if}
    </div>
</div>
<div class="help-block">
    [[The Twitter counter is updating with a delay which sometimes may take several hours. This depends on caching settings of Twitter servers. No further action is required on your part.]]
</div>        
  
{require component="jquery" file="jquery.js"}
<script type="text/javascript">
	{literal}
	$(document).ready(function()
	{
		$('tr.facebookInput').hide();
		$('tr.twitterInput').hide();

		$('a[href="#facebook"]').click(function()
		{
			$('tr.facebookInput').show();
			return false;
		});
		$('a[href="#twitter"]').click(function()
		{
			$('tr.twitterInput').show();
			return false;
		});
	});
	{/literal}
</script>
