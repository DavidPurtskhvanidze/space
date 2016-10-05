<div class="page-content">
  <div class="row">
    <div class="col-xs-12">
      {if $listing_search.total_found > 0}
        {display_error_messages}
        {display_success_messages}

        {assign var="listings_number" value=$listing_search.total_found}
        <div class="table-header">[[$listings_number listings found]]</div>
        <div class="table-responsive">
          <div class="dataTables_wrapper" role="grid">
            <div class="row">
              <div class="col-sm-4">
                {include file="miscellaneous^items_per_page_selector.tpl" search=$listing_search}
              </div>
              <div class="col-sm-8">
                <div class="btn-group">
                    {if $REQUEST.hidePictures == 1}
                      <a class="btn btn-primary btn-xs white" href="?action=restore&amp;searchId={$listing_search.id}&amp;hidePictures=0">[[Display pictures]]</a>
                    {else}
                      <a class="btn btn-primary btn-xs white" href="?action=restore&amp;searchId={$listing_search.id}&amp;hidePictures=1">[[Hide pictures]]</a>
                    {/if}
                </div>

                {$url = "?action=restore&amp;searchId={$listing_search.id}"}
                {include file="miscellaneous^sorting_field_selector.tpl" url=$url search=$listing_search sortingFields=$sortingFields.system  moreSortingFields=$sortingFields.category}

                <div class="btn-group">
                  <button class="btn btn-primary dropdown-toggle btn-xs actionWithSelected" data-toggle="dropdown">
                    [[Actions with selected]]
                    <i class="icon-angle-down icon-on-right"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-info pull-right actionList">
                    <li><a href="{page_path module='classifieds' function='listing_actions'}?action=Activate">[[Activate]]</a></li>
                    <li><a href="{page_path module='classifieds' function='listing_actions'}?action=Deactivate">[[Deactivate]]</a></li>
                    <li><a href="{page_path module='classifieds' function='listing_actions'}?action=Edit+Packages">[[Edit Packages]]</a></li>
                    <li><a href="{page_path module='classifieds' function='listing_actions'}?action=Delete" rel="[[Are you sure you want to delete checked listings?:raw]]">[[Delete]]</a></li>
                    <li><a href="{page_path module='classifieds' function='listing_actions'}?action=Reject">[[Reject]]</a></li>
                    <li class="dropdown-hover">
                      <a class="clearfix" tabindex="-1" href="#">
                        <span class="pull-left">[[Assign Package]]</span>
                        <i class="icon-caret-right pull-right"></i>
                      </a>
                      <ul class="dropdown-menu dropdown-menu-right pull-right">
                        {foreach from=$membership_plans item=membership_plan}
                          <li class="dropdown-hover">
                            <a class="clearfix" tabindex="-1" href="#">
                              <span class="pull-left">{$membership_plan.name}</span>
                              <i class="icon-caret-right pull-right"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right pull-right">
                              {foreach from=$membership_plan.packages item=package}
                                <li><a href="{page_path module='classifieds' function='listing_actions'}?action=Assign+Package&package_sid={$package.sid}">{$package.name}</a></li>
                              {/foreach}
                            </ul>
                          </li>
                        {/foreach}
                      </ul>
                    </li>
                    {foreach from=$listingMassActions item='action'}
                      <li><a href="{$GLOBALS.site_url}{$action->getUri()}">[[{$action->getCaption()}]]</a></li>
                    {/foreach}
                  </ul>
                </div>
              </div>
            </div>
            <form name="itemSelectorForm" method="post" action="{page_path module='classifieds' function='listing_actions'}">
                {CSRF_token}
                <input type="hidden" name="searchId" value="{$listing_search.id}" />
              <table class="table table-hover table-striped">
                <thead>
                  <tr>
                   <th class="center align-middle">
                     <label>
                         <input type="checkbox" class="ace check-all" />
                         <span class="lbl"></span>
                     </label>
                   </th>
                   {if !$REQUEST.hidePictures}
                      <th class="hidden-xs">Picture</th>
                   {/if}
                   <th class="hidden-sm hidden-xs">Info</th>
                   <th class="hidden-sm hidden-xs">[[ID]]</th>
                   <th class="hidden-sm hidden-xs">[[Posted by]]</th>
                   <th class="hidden-xs">[[Category]]</th>
                   <th>[[Status]]</th>
                   <th>[[Actions]]</th>
                  </tr>
                </thead>
                <tbody>
                {foreach from=$listings item="listing" name="listings_block"}
                  <tr data-item-sid="{$listing.sid}">
                    <td class="center MiddleAlign">
                      <label>
                        <input class="ace" type="checkbox" name="listings[{$listing.id}]" value="1" id="checkbox_{$smarty.foreach.listings_block.iteration}" />
                          <span class="lbl"></span>
                      </label>
                    </td>
                    {if !$REQUEST.hidePictures}
                      <td class="hidden-xs">
                        <a href="{page_path id='display_listing'}?listing_id={$listing.id}&amp;searchId={$listing_search.id}">
                          {if $listing.pictures.numberOfItems > 0}
                            {listing_image pictureInfo=$listing.pictures.collection.0 thumbnail=1}
                          {else}
                            <img class="img-thumbnail" src="{url file='main^no_image_available_small.png'}" alt="[[No photos:raw]]" />
                          {/if}
                        </a>
                      </td>
                    {/if}
                    <td>
                      <ul class="list-unstyled">
                        <li><h4><a href="{page_path id='display_listing'}?listing_id={$listing.id}&amp;searchId={$listing_search.id}" title="{$listing|cat:""|strip_tags:false}">{$listing}</a></h4></li>
                        <li>
                          <ul>
                            <li>{$listing.pictures.numberOfItems} [[pictures]]</li>
                            <li>{module name="listing_comments" function="display_comment_control" listing=$listing controll="NUMBER_OF_COMMNETS"}</li>
                            <li>{$listing.views} [[views]]</li>
                            <li>{if $listing.activation_date.isNotEmpty}[[Activated on]] [[$listing.activation_date]]{else}[[Never been active]]{/if}</li>
                        </ul>
                        </li>
                      </ul>
                    </td>
                    <td class="center align-middle hidden-sm hidden-xs">{$listing.id}</td>
                    <td class="center align-middle hidden-sm hidden-xs">{if $listing.user_sid.value == 0}[[Administrator]]{else}{$listing.user.username}{/if}</td>
                    <td class="center align-middle hidden-xs">[[Categories!{$listing.type.caption}]]</td>
                    <td>
                      {if $listing.active.isTrue}<span class="label label-sm arrowed-right label-success">[[Active]]</span>{else}<span class="label label-sm arrowed-right label-warning">[[Inactive]]</span>{/if}
                      {if !$listing.moderation_status.isEmpty}
                        {assign var="moderation_status" value=$listing.moderation_status|strtolower}
                        <br>
                        <span class="label label-sm arrowed-right {if $moderation_status=="approved"}label-success{else}label-warning{/if}">[[$moderation_status]]</span>
                      {/if}
                    </td>
                    <td>
                      <div class="btn-group">
                        {capture assign="returnBackUri"}/manage_listings/?action=restore&searchId={$listing_search.id}{/capture}
                        {module name="listing_comments" function="display_comment_control" listing=$listing controll="ADD_OR_MANAGE" returnBackUri=$returnBackUri}
	                    {if $listing.active.isTrue}
                          <a class="itemControls deactivate btn btn-xs btn-inverse" href="{page_path module='classifieds' function='listing_actions'}?searchId={$listing_search.id}&amp;listings[{$listing.id}]=1&amp;action=Deactivate" title="[[Deactivate]]">
                            <i class="icon-eye-close bigger-120"></i>
                          </a>
                        {else}
                          <a class="itemControls activate btn btn-xs btn-inverse" href="{page_path module='classifieds' function='listing_actions'}?searchId={$listing_search.id}&amp;listings[{$listing.id}]=1&amp;action=Activate"title="[[Activate]]">
                            <i class="icon-eye-open bigger-120"></i>
                          </a>
                        {/if}
                        {if strcasecmp($listing.moderation_status.rawValue, 'PENDING') == 0 || strcasecmp($listing.moderation_status.rawValue, 'APPROVED') == 0}
                          <a class="itemControls reject btn btn-xs btn-warning" href="{page_path module='classifieds' function='listing_actions'}?searchId={$listing_search.id}&amp;listings[{$listing.id}]=1&amp;action=Reject" title="[[Reject]]">
                            <i class="icon-ban-circle bigger-120"></i>
                          </a>
                        {/if}
                        <a class="itemControls edit btn btn-xs btn-info" href="{page_path id='edit_listing'}?searchId={$listing_search.id}&amp;listing_id={$listing.id}" title="[[Edit:raw]]">
                          <i class="icon-edit bigger-120"></i>
                        </a>
                        <a class="itemControls delete btn btn-xs btn-danger" href="{page_path module='classifieds' function='listing_actions'}?searchId={$listing_search.id}&amp;listings[{$listing.id}]=1&amp;action=Delete" onclick="return confirm('[[Are you sure you want to delete this listing?:raw]]')" title="[[Delete]]">
                          <i class="icon-trash bigger-120"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                {/foreach}
                </tbody>
              </table>
            </form>
            <div class="row">
              <div class="col-sm-6"></div>
              <div class="col-sm-6">
                {include file="miscellaneous^page_selector.tpl" search=$listing_search}
              </div>
            </div>
          </div>
        </div>


        {include file="miscellaneous^search_result_item_actions_js.tpl"}
        {require component="jquery" file="jquery.js"}
        <script type="text/javascript">
          var noSelectedItemsMessage = "[[You have not selected any items. Please select one or more items and proceed with actions.:raw]]";
          {literal}
          $(document).ready(function(){
            $(".actionList a").click(function(){
              if (!$(this).attr("rel") || confirm($(this).attr("rel")))
              {
                window.location.href = $(this).attr("href") + "&" + $("form[name='itemSelectorForm']").serialize();
              }
              return false;
            });

           $(".actionWithSelected").click(function(){
              if (!$('input[name^=listings]:checked').length)
              {
                $(this).addClass("disabled");
                alert(noSelectedItemsMessage);
              }
            });

            $('.table tr input:checkbox').on('change', function(){
              if($(this).prop("checked")){
                $(".actionWithSelected").removeClass("disabled");
              }
            });

            $('table th input:checkbox').on('click' , function(){
              var that = this;
              $(this).closest('table').find('tr > td:first-child input:checkbox')
                  .each(function(){
                    this.checked = that.checked;
                    $(this).closest('tr').toggleClass('selected');
                  });
            });
          });
          {/literal}
        </script>
        {include file="miscellaneous^multilevelmenu_js.tpl"}


      {else}

        <p class="error">[[There are no listings available that match your search criteria. Please try to broaden your search criteria.]]</p>

      {/if}
    </div>
  </div>
</div>
