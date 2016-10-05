<div class="listingRepostSettings control-group">

  <label class="control-label bolder blue">[[Do not repost this listing in]]</label>
  <div class="checkbox">
    <label>
      <input class="ace" type="checkbox" id="doNotRepostToFacebook" name="doNotRepostToFacebook" {if $doNotRepostToFacebook}checked="checked"{/if} value="1" {if $facebookRepostStatus == 0}disabled{/if} />
      <span class="lbl">[[Facebook]]{if $facebookRepostStatus == 0} ([[Disabled]]) {/if}</span>
    </label>
  </div>
  <div class="checkbox">
    <label>
      <input class="ace" type="checkbox" id="doNotRepostToTwitter" name="doNotRepostToTwitter" {if $doNotRepostToTwitter}checked="checked"{/if} value="1" {if $twitterRepostStatus == 0}disabled{/if}/>
      <span class="lbl"> [[Twitter]]{if $twitterRepostStatus == 0} ([[Disabled]]) {/if}</span>
    </label>
  </div>
	<div class="space-10"></div>
  {assign var="repostSectionUrl" value={page_path id='settings'}|cat:"#SocialNetworks"}
  {if $twitterRepostStatus == 0 || $facebookRepostStatus == 0}
    <div class="alert alert-info">[[You can enable listing repost on the System Setting page under the <a href="$repostSectionUrl">Social Networks section</a>.]]</div>
  {/if}
</div>
