<div class="editListing">
	<div class="breadcrumbs">
    <ul class="breadcrumb">
      <li><a href="{$GLOBALS.site_url}/manage_listings/?action=restore&amp;searchId={$searchId}">[[Manage Listings]]</a></li>
      <li>[[Edit Listing]]</li>
    </ul>
  </div>

  <div class="page-content">
	  <div class="page-header">
      <h1>[[Edit Listing #$listing_id]], {$listing}</h1>
    </div>

    <div class="row">
      {display_error_messages}
      {display_success_messages}

      {include file="miscellaneous^dialog_window.tpl"}
      <div class="col-xs-12">
        <ul class="list-inline">
          {capture assign="returnBackUrl"}{page_path id='edit_listing'}?searchId={$searchId}&listing_id={$listing_id}{/capture}
          {capture assign="returnBackUri"}{page_uri id='edit_listing'}?searchId={$searchId}&listing_id={$listing_id}{/capture}
          {module name="classifieds" function="display_edit_listing_page_controls" listingSid=$listing_id returnBackUri=$returnBackUrl|urlencode}
          {if !empty($userinfo_username)}
            {if $userinfo_trusted}
              <li><a href="{page_path module='users' function='change_user_trusted_status'}?action=make_user_untrusted&user_sids[]={$userinfo_sid}&returnBackUri={$returnBackUri|urlencode}">[[Make the User $userinfo_username Untrusted]]</a></li>
            {else}
              <li><a href="{page_path module='users' function='change_user_trusted_status'}?action=make_user_trusted&user_sids[]={$userinfo_sid}&returnBackUri={$returnBackUri|urlencode}">[[Make the User $userinfo_username Trusted]]</a></li>
            {/if}
          {/if}
          {module name="listing_comments" function="display_comment_control" listing=$listing returnBackUri=$returnBackUri wrapperTemplate='comment_controll_ul_wrapper.tpl' controll="ADD" }
          {module name="listing_comments" function="display_comment_control" listing=$listing returnBackUri=$returnBackUri wrapperTemplate='comment_controll_ul_wrapper.tpl' controll="MANAGE" includeCommentCount=1}
        </ul>
      </div>

      <div class="col-xs-12">
        [[This listing is]]
        {if $listing_info.active}<span class="text-success">[[Active]]</span>{else}<span class="text-warning">[[Inactive]]</span>{/if}
        {if !empty($listing_info.moderation_status)}
          {assign var="moderation_status" value=$listing_info.moderation_status|strtolower}
          [[and]] <span class="{if $moderation_status=="approved"}text-success{else}text-warning{/if}"> [[$moderation_status]]</span>
        {/if}
        | {capture assign="returnBackUri"}{page_uri id='edit_listing'}?searchId={$searchId}&listing_id={$listing_id}{/capture}
        {if $listing_info.active}
          <a class="btn btn-link deactivate" href="{page_path module='classifieds' function='listing_actions'}?searchId={$searchId}&amp;listings[{$listing_id}]=1&amp;action=Deactivate&amp;returnBackUri={$returnBackUri|urlencode}">[[Deactivate]]</a>
        {else}
          <a class="btn btn-link activate" href="{page_path module='classifieds' function='listing_actions'}?searchId={$searchId}&amp;listings[{$listing_id}]=1&amp;action=Activate&amp;returnBackUri={$returnBackUri|urlencode}">[[Activate]]</a>
        {/if}
        | <a class="btn btn-link view" href="{page_path id='display_listing'}?searchId={$searchId}&amp;listing_id={$listing_id}">[[View Listing]]</a>
        | <a class="btn btn-link" href="{page_path module='classifieds' function='manage_pictures'}?searchId={$searchId}&amp;listing_id={$listing_id}">{if $listing_info.pictures == 0}[[Add Pictures]]{else}[[Edit Pictures]] ({$listing_info.pictures}){/if}</a>
      </div>

      <div class="col-xs-12 space-10"></div>

      <div class="listingDetails col-sm-6">
        <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60 "></i>) are mandatory.]]</div>
        <form class="form form-horizontal" method="post" enctype="multipart/form-data">
          <input type="hidden" name="action" value="save_info" />
          <input type="hidden" name="listing_id" value="{$listing_id}" />
          <input type="hidden" name="searchId" value="{$searchId}" />

          {foreach from=$form_fields item=form_field}

            {if $form_field.id == 'AvailabilityCalendar'}
              <div class="form-group">
                <label class="col-sm-3 control-label">
                  [[$form_fields.AvailabilityCalendar.caption]]
                  {if $form_field.AvailabilityCalendar.is_required}<i class="icon-asterisk smaller-60 "></i>{/if}
                </label>
                <div class="col-sm-8">{input property=AvailabilityCalendar}</div>
              </div>
            {else}
              <div class="form-group">
                <label class="col-sm-3 control-label">
                  [[$form_field.caption]]
                  {if $form_field.is_required} <i class="icon-asterisk smaller-60 "></i>{/if}
                </label>
                <div class="col-sm-8">
                  {input property=$form_field.id}
                </div>
              </div>
            {/if}
          {/foreach}
          <div class="clearfix form-actions">
            <input type="submit" value="[[Save:raw]]" class="btn btn-default">
          </div>

        </form>
      </div>

      {if !empty($userinfo_username)}
        <div class="col-sm-5 col-sm-offset-1">
          <h4 class="lighter">[[Listing Package Details]]</h4>
          <table class="table table-striped">
            <tr>
              <td><span class="fieldCaption name">[[Package name]]</span></td>
              <td>[[$package.name]]</td>
            </tr>
            <tr>
              <td><span class="fieldCaption description">[[Package description]]</span></td>
              <td>[[$package.description]]</td>
            </tr>
            {foreach from=$package.packageDetails key="packageDetailId" item="packageDetail"}
              <tr>
                <td><span class="fieldCaption {$packageDetailId}">[[$packageDetail.caption]]</span></td>
                <td><span class="fieldValue {$packageDetailId}">{$packageDetail.value}</span></td>
              </tr>
            {/foreach}
          </table>
        </div>
      {/if}

    </div>
  </div>
</div>
