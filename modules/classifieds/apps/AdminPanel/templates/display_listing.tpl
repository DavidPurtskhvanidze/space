<div class="displayListing">
	<div class="breadcrumbs">
    <ul class="breadcrumb">
      <li><a href="{$GLOBALS.site_url}/manage_listings/?action=restore&amp;searchId={$listing_search.id}">[[Manage Listings]]</a></li>
      <li>[[Display Listing]]</li>
    </ul>
  </div>

  <div class="page-content">
    <div class="page-header">
    {if $errors}
      {display_error_messages}
    {else}
      <h1 class="lighter">[[Display Listing #$listing_id]], {$listing}</h1>
      {include file="miscellaneous^dialog_window.tpl"}
    </div>

    <div class="row">
        {display_success_messages}
			<div class="col-xs-12">
        <div class="listingControls">
	        <ul class="list-inline">
		        {capture assign="returnBackUrl"}{page_path id='display_listing'}?searchId={$listing_search.id}&listing_id={$listing_id}{/capture}
		        {capture assign="returnBackUri"}{page_uri id='display_listing'}?listing_id={$listing_id}&searchId={$listing_search.id}{/capture}
		        <li>
			        <a href="{page_path id='edit_listing'}?listing_id={$listing.id}&amp;searchId={$listing_search.id}" title="[[Edit Listing:raw]]">[[Edit Listing]]</a>
		        </li>
		        {module name="classifieds" function="display_display_listing_page_controls" listingSid=$listing_id returnBackUri=$returnBackUrl|urlencode}
		        {if !$userinfo_sid}
		        {elseif $userinfo_trusted}
			        <li>
				        <a href="{page_path module='users' function='change_user_trusted_status'}?action=make_user_untrusted&user_sids[]={$userinfo_sid}&returnBackUri={$returnBackUri|urlencode}">[[Make the User $userinfo_username Untrusted]]</a>
			        </li>
		        {else}
			        <li>
				        <a href="{page_path module='users' function='change_user_trusted_status'}?action=make_user_trusted&user_sids[]={$userinfo_sid}&returnBackUri={$returnBackUri|urlencode}">[[Make the User $userinfo_username Trusted]]</a>
			        </li>
		        {/if}
		        <li>
			        {module name="listing_comments" function="display_comment_control" listing=$listing returnBackUri=$returnBackUri wrapperTemplate='comment_controll_ul_wrapper.tpl' controll="ADD" }
		        </li>
		        <li>
			        {module name="listing_comments" function="display_comment_control" listing=$listing returnBackUri=$returnBackUri wrapperTemplate='comment_controll_ul_wrapper.tpl' controll="MANAGE" includeCommentCount=1}
		        </li>
	        </ul>
        </div>
			</div>
			<div class="space-14"></div>
			<div class="col-xs-12">
        {if $listing.active}
          {assign var="listingStatus" value="active"}
        {elseif strcasecmp($listing.moderation_status, 'PENDING') == 0}
          {assign var="listingStatus" value="pending approval"}
        {elseif strcasecmp($listing.moderation_status, 'REJECTED') == 0}
          {assign var="listingStatus" value="rejected"}
        {else}
          {assign var="listingStatus" value="inactive"}
        {/if}

          [[This listing is]]
          {if $listing.active.isTrue}<span class="text-success">[[Active]]</span>{else}<span class="text-warning">[[Inactive]]</span>{/if}
          {if $listing.moderation_status.isNotEmpty}
            {assign var="moderation_status" value=$listing.moderation_status|strtolower}
            [[and]]
            <span class="{if $moderation_status=="approved"}text-success{else}text-warning{/if}">[[$moderation_status]]</span>
          {/if}
          | {capture assign="returnBackUri"}{page_uri id='display_listing'}?searchId={$listing_search.id}&listing_id={$listing_id}{/capture}
          {if $listing.active.isTrue}
            <a class="itemControls deactivate" href="{page_path module='classifieds' function='listing_actions'}?searchId={$listing_search.id}&amp;listings[{$listing_id}]=1&amp;action=Deactivate&amp;returnBackUri={$returnBackUri|urlencode}">[[Deactivate]]</a>
          {else}
            <a class="itemControls activate" href="{page_path module='classifieds' function='listing_actions'}?searchId={$listing_search.id}&amp;listings[{$listing_id}]=1&amp;action=Activate&amp;returnBackUri={$returnBackUri|urlencode}">[[Activate]]</a>
          {/if}

			</div>

        <div class="col-sm-8 no-padding-left">
          <h4 class="blue">[[Listing Details]]</h4>
          {include file="listing_images.tpl" listing=$listing}
          <div class="space-14"></div>
          {module name="listing_feature_slideshow" function="display_slideshow" listing=$listing}
          <table class="table table-striped">
            <tr>
              <td><span class="fieldCaption id">[[Listing ID]]</span></td>
              <td>{$listing.id}</td>
            </tr>
            <tr>
              <td><span class="fieldCaption category">[[Category]]</span></td>
              <td>[[Categories!{$listing.type.caption}]]</td>
            </tr>
            <tr>
              <td><span class="fieldCaption activationDate">[[Activation Date]]</span></td>
              <td>{$listing.activation_date}</td>
            </tr>
            <tr>
              <td><span class="fieldCaption expirationDate">[[Expiration Date]]</span></td>
              <td>{$listing.expiration_date}</td>
            </tr>
            <tr>
              <td><span class="fieldCaption username">[[Listing User]]</span></td>
              <td>
                {if $listing.user.isNotEmpty}
                  <a href="mailto:{$listing.user.email}">{$listing.user.username}</a>
                {else}
                  [[Administrator]]
                {/if}
              </td>
            </tr>
            <tr>
              <td><span class="fieldCaption views">[[FormFieldCaptions!# of Views]]</span></td>
              <td>{$listing.views}</td>
            </tr>
            {assign var="fieldsToExclude"
              value=[
                  'sid',
                  'id',
                  'active',
                  'pictures',
                  'views',
                  'keywords',
                  'activation_date',
                  'expiration_date',
                  'moderation_status',
                  'type',
                  'category_sid',
                  'category',
                  'user',
                  'user_sid',
                  'username',
                  'package',
                  'listing_package',
                  'feature_youtube_video_id',
                  'saved',
                  'inComparison',
                  'numberOfComments'
              ]}
            {foreach from=$form_fields item=form_field}
              {if !in_array($form_field.id, $fieldsToExclude)
                && $listing[$form_field.id].type != 'video'
                && $listing[$form_field.id].type != 'calendar'
              }
                <tr>
                  <td><span class="fieldCaption {$form_field.id}">[[$form_field.caption]]</span></td>
                  <td>{display property=$form_field.id}</td>
                </tr>
              {elseif $listing[$form_field.id].type == 'video' && $listing[$form_field.id].isNotEmpty}
                <tr>
                  <td><span class="fieldCaption {$form_field.id}">[[$form_field.caption]]</span></td>
                  <td>{display property=$form_field.id}</td>
                </tr>
              {elseif $listing[$form_field.id].type == 'calendar'}
                <tr>
                  <td colspan="2">
                    <span class="fieldCaption {$form_field.id}">[[$form_field.caption]]</span>
                    {display property=$form_field.id}
                  </td>
                </tr>
              {elseif $form_field.id == 'feature_youtube_video_id'}
                {module name="listing_feature_youtube" function="display_youtube" listing=$listing form_field=$form_field}
              {/if}
            {/foreach}
          </table>
        </div>

        {if $listing.user.isNotEmpty}
          <div class="col-sm-4">
            <h4>[[Listing Package Details]]</h4>
            <table class="table table-striped">
              <tr>
                <td class="fieldCaption"><span class="fieldCaption name">[[Package name]]</span></td>
                <td>[[$package.name]]</td>
              </tr>
              <tr>
                <td class="fieldCaption"><span class="fieldCaption description">[[Package description]]</span></td>
                <td>[[$package.description]]</td>
              </tr>
              {foreach from=$package.packageDetails key="packageDetailId" item="packageDetail"}
                <tr>
                  <td class="fieldCaption"><span class="fieldCaption {$packageDetailId}">[[$packageDetail.caption]]</span></td>
                  <td><span class="fieldValue {$packageDetailId}">{$packageDetail.value}</span></td>
                </tr>
              {/foreach}
            </table>
          </div>
        {/if}

      {/if}
    </div>
  </div>
</div>
