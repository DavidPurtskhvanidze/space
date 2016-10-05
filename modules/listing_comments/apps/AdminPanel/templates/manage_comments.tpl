<div class="page-content">
  <div class="row">
    {display_success_messages}
    {display_error_messages}
    {if $listing_search.total_found > 0}
      {include file="miscellaneous^dialog_window.tpl"}

      <div class="col-xs-12">
        <div class="table-header"></div>
        <div class="table-responsive">
          <div class="dataTables_wrapper" role="grid">
            <div class="row">
              <div class="searchResultControls">

                <div class="col-sm-6">
                  <div class="btn-group">
                      <label>
                          <input class="ace" type="checkbox" id="checkAll"/>
                          <span class="lbl"></span>
                      </label>
                  </div>
                  <div class="btn-group">
                    <button class="actionWithSelected btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown">
                      [[Actions with selected]]
                      <i class="icon-angle-down icon-on-right"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-info pull-right actionList">
                      <li><a href="{page_path module='listing_comments' function='comment_actions'}?action=Publish" onclick="return submitItemSelectorForm(this, false)">[[Publish]]</a></li>
                      <li><a href="{page_path module='listing_comments' function='comment_actions'}?action=Hide" onclick="return submitItemSelectorForm(this, false)">[[Hide]]</a></li>
                      <li><a href="{page_path module='listing_comments' function='comment_actions'}?action=Delete" onclick="return submitItemSelectorForm(this, '[[Are you sure that you want to delete selected comments?:raw]]')">[[Delete]]</a></li>
                      <li><a href="{page_path module='listing_comments' function='comment_actions'}?action=Make+User+Trusted" onclick="return submitItemSelectorForm(this, false)">[[Make User Trusted]]</a></li>
                      <li><a href="{page_path module='listing_comments' function='comment_actions'}?action=Make+User+Untrusted" onclick="return submitItemSelectorForm(this, false)">[[Make User Untrusted]]</a></li>
                    </ul>
                  </div>
                </div>

                <div class="col-sm-6 text-right">
                  {include file="miscellaneous^items_per_page_selector.tpl" search=$listing_search}
                </div>

              </div>
            </div>

            <form method="post" action="{page_path module='listing_comments' function='comment_actions'}" name="itemSelectorForm">
              {CSRF_token}
              <input type="hidden" name="searchId" value="{$listing_search.id}" />
                {foreach from=$listings item="listing"}
                  <div class="thumbnail margin-10-0">

                    <div class="row">
                      <div class="col-sm-6">
                        <h4>
                            <a href="{page_path id='display_listing'}?listing_id={$listing.id}" onclick="javascript:window.open(this.href, '_blank'); return false;">#{$listing.id} {$listing}</a>
                        </h4>
                      </div>
                      <div class="col-sm-6 text-right">
                        <div class="btn-group">
                            <h4>
                              {capture assign="returnBackUri"}{page_uri module='listing_comments' function='manage_comments'}?action=restore&amp;searchId={$search.id}{/capture}
                              <a class="addComment btn btn-link" onclick='return openDialogWindow("[[Add a comment:raw]]", this.href, 500, true)' href="{page_path module='listing_comments' function='add_listing_comment'}?listingSid={$listing.sid}&amp;returnBackUri={$returnBackUri|urlencode}">
                                <i class="icon-plus-sign bigger-120 green"></i>
                                [[Add a comment]]
                              </a>
                            </h4>
                        </div>
                      </div>
                    </div>

                    {module name="listing_comments" function="display_listing_comments" inheritRequest=false QUERY_STRING="listing_sid[equal]="|cat:$listing.sid commentsSidToDisplay=$commentsSidToDisplay oneOfTheParentsIsHidden=false searchId=$search.id selectedCommets=$selectedCommets}
                  </div>
                {/foreach}
            </form>

            <div class="row">
              <div class="col-sm-12">
                <div class="dataTables_paginate paging_bootstrap">
                  {include file="miscellaneous^page_selector.tpl" search=$listing_search}
                </div>
              </div>
            </div>

            {require component="jquery-cookie" file="jquery.cookie.js"}
            <script>
              var requestAction = "{$REQUEST.action}";
              var noSelectedItemsMessage = "[[You have not selected any items. Please select one or more items and proceed with actions.:raw]]";
              {literal}
              function submitItemSelectorForm(anchor, confirmationMessage)
              {
                if (confirmationMessage && !confirm(confirmationMessage))
                {
                  return false;
                }
                window.location.href = $(anchor).attr("href") + "&" + $("form[name='itemSelectorForm']").serialize();
                return false;
              }

              $(document).ready(function(){

                $('a.replies').click(function(event){
                    event.preventDefault();
                    var commentSid = $(this).data('commentSid');
                    $('#collapse' + commentSid).toggleClass('in');
                });

                $('#checkAll').on('click' , function(){
                  var that = this;
                  $(this).closest('.dataTables_wrapper').find('.comments  .row input:checkbox')
                      .each(function(){
                        this.checked = that.checked;
                        $(this).closest('.thumbnail').toggleClass('selected');
                      });
                });

                $(".searchResultControls .actionList a").click(function(){
                  window.location.href = $(this).attr("href") + "&" + $("form[name='itemSelectorForm']").serialize();
                  return false;
                });

                $(".actionWithSelected").click(function(){
                  if (!$('input[name^=selectedCommets]:checked').length)
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

                var cookie = $.cookie("linksToExpand");
                if (requestAction == 'restore' && cookie)
                {
                  $.each(cookie.split(/,/), function(index, value) {
                    $("#" + value).click();
                  });
                }
                else
                {
                  $.cookie("linksToExpand", null);
                }

                $(this).find("div.commentBody").each(function()
                {
                  if ($(this).css("display") == "none")
                  {
                    $(this).parents("div.replies").filter(":first").parent().find("div.commentBody").filter(":first").find("a.viewThread").show();
                    $(this).parent().find("div.commentBody").find("a.viewThread").show();
                  }
                });
              });

              $("a.showAllComments").click(function () {
                $(this).parents("div.listingComments").find("div.commentBody").show(300);
                $(this).parents("div.listingComments").find("span.showAllCommentsLink").hide();
                $(this).parents("div.listingComments").find("a.viewThread").hide();
                addLinkToCookies(this);
                return false;
              });

              $("a.viewThread").click(function () {
                $(this).parents("div.comment").children("div.commentBody").show(300);
                $(this).parents("div.comment").filter(':first').find("div.commentBody").show(300);
                $(this).parents("div.comment").find("a.viewThread").hide();
                addLinkToCookies(this);
                return false;
              });

              function addLinkToCookies(target)
              {
                var cookie = $.cookie("linksToExpand");
                var linksToExpand = cookie ? cookie.split(/,/) : new Array();
                var targetId = $(target).attr("id");
                if ($.inArray(targetId, linksToExpand) == -1)
                {
                  linksToExpand.push(targetId);
                }
                $.cookie("linksToExpand", linksToExpand.join(','));
              }

              {/literal}
            </script>
            {include file="miscellaneous^multilevelmenu_js.tpl"}
          </div>
        </div>
      </div>
    {else}

      <p class="error">[[There are no comments available that match your search criteria. Please try to broaden your search criteria.]]</p>

    {/if}

  </div>
</div>
