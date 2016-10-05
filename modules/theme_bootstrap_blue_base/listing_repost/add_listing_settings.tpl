{if $facebookIsSetUp || $twitterIsSetUp}
    {strip}
	<div class="listingRepostSettings">
        <fieldset>
            <legend>[[Listing Repost]]</legend>
            {assign var="profilePageUrl" value={page_path id='user_profile'}}
            {if ($twitterIsSetUp && $twitterRepostStatus == 0)}
                <div class="bg-info">
                    [[You can enable listing repost on your <a href="$profilePageUrl">profile page</a>.]]
                </div>
            {/if}
            <div class="row">
                <div class="col-sm-5 vcenter text-right">[[Do not repost this listing in]]</div>
                <div class="col-sm-4 vcenter">
                    {if $twitterIsSetUp}
                        <div class="custom-form-control">
                            <input type="checkbox" id="doNotRepostToTwitter" name="doNotRepostToTwitter" {if $doNotRepostToTwitter}checked="checked"{/if} value="1" {if $twitterRepostStatus == 0}disabled{/if}/>
                            <label class="checkbox" for="doNotRepostToTwitter">[[Twitter]]{if $twitterRepostStatus == 0} ([[Disabled]]) {/if}</label>
                        </div>
                    {/if}
                </div>
            </div>
        </fieldset>
	</div>
    {/strip}
{/if}
