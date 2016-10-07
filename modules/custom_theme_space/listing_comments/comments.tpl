{if !$REQUEST.script_included}
    {require component="jquery" file="jquery.js"}
    <script type="text/javascript">
        var siteUrl = "{$GLOBALS.site_url}";
        var displayCommentPageUri = "{page_uri id='comments'}";
        function showPermanentLink(listingId, commentId)
        {
            prompt("[[Please copy the permanent link of this comment to your clipboard]]", siteUrl + displayCommentPageUri + listingId + "/#comment" + commentId);
        }
        $(function () {
            var hash = window.location.hash;
            var isValidHtmlId = (hash.charAt(1).match(/[A-Za-z]/) !== null);
            if (hash.length > 0 && isValidHtmlId) {
                $(hash).parents(".collapse").addClass("in");
                $(window.location).attr('hash', hash);
            }
        });
    </script>
{/if}

{assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
{assign var='current_uri' value=$current_uri|urlencode}

<div class="comments">
    {foreach from=$comments item=comment}
        <div class="thumbnail {if $comment.published.isFalse}notPublishedComment{/if}" {if $comment.published.isFalse} style="background:{get_custom_setting id='hidden_comments_background_color' theme=$GLOBALS.current_theme}"{/if}>
            {if $comment.published.isFalse}<p class="text-danger">[[This comment has not been published and is currently visible only to its author and the owner of the listing.]]</p>{/if}
            <div class="row">
                <div class="col-sm-2 col-xs-3 text-center">
                    <a name="comment{$comment.sid}" id="comment{$comment.sid}">
                        {if $comment.user.ProfilePicture.exists && $comment.user.ProfilePicture.ProfilePicture.name}
                            <img class="img-responsive" src="{$comment.user.ProfilePicture.ProfilePicture.url}" />
                        {else}
                            <img class="img-responsive" src="{url file='main^user.png'}" alt="[[No photos:raw]]" />
                        {/if}
                    </a>
                </div>
                <div class="col-sm-10 col-xs-9">
                    <div class="row">
                        <div class="col-sm-9 col-xs-6">
                            <div class="dropdown">
                                <a data-toggle="dropdown" href="#">
                                    {if $comment.user.isNotEmpty}{$comment.user.username.value}{else}[[Admin]]{/if}
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    {if $comment.user.isNotEmpty}
                                        <li role="presentation">
                                            {$username = $comment.user.username.value}
                                            <a href="{page_path id='users'}{$comment.user.id}/{if $comment.user.DealershipName.exists}{$comment.user.DealershipName.value|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{/if}{$comment.user.FirstName.value|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$comment.user.LastName.value|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[View $username's Profile]]</a>
                                        </li>
                                        <li role="presentation">
                                            <a href="{page_path id='users_contact'}{$comment.user.id}/{if $comment.user.DealershipName.exists}{$comment.user.DealershipName.value|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{/if}{$comment.user.FirstName.value|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$comment.user.LastName.value|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html?listing_sid={$comment.listing_sid}&amp;returnBackUri={$current_uri}%26restoreActiveTab%3Drestore">[[Contact User]]</a>
                                        </li>
                                    {else}
                                        <li role="presentation">
                                            <a href="{page_path id='contact'}?returnBackUri={$current_uri}%26restoreActiveTab%3Drestore">[[Contact Admin]]</a>
                                        </li>
                                    {/if}
                                    <li role="presentation">
                                        <a href="{page_path module='miscellaneous' function='report_improper_content'}?objectType=comment&amp;objectId={$comment.sid}&amp;returnBackUri={$current_uri}%26restoreActiveTab%3Drestore">[[Report This]]</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#" onclick="showPermanentLink({$comment.listing_sid}, {$comment.id});return false;">[[Permanent Link]]</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-6 text-right">
                            {include file="miscellaneous^rating.tpl" rating=$comment.ListingRating}
                        </div>
                    </div>
                </div>
                <div class="col-sm-10 col-xs-12">
                    <p>
                        {$comment.comment}
                    </p>
                    <small>[[$comment.posted]]</small>
                </div>
                <div class="col-sm-10 col-sm-offset-2 col-xs-12">
                    <ul class="list-inline commentActions">
                        {assign var="numberOfReplies" value=$comment.numberOfReplies.value}
                        <li>
                            {if $GLOBALS.current_user.logged_in}
                                <a id='{$comment.id}' data-comment-sid='{$comment.id}' class='showReplayToCommentForm' href='{page_path id='comment_add'}?listingSid={$comment.listing_sid}&amp;returnBackUri={$current_uri}&amp;commentSid={$comment.id}'>[[Reply to the comment]]</a>
                            {else}
                                <a id='{$comment.id}' data-comment-sid='{$comment.id}' href='{page_path id='comment_add'}?listingSid={$comment.listing_sid}&amp;returnBackUri={$current_uri}&amp;restoreActiveTab=restore&amp;commentSid={$comment.id}'>[[Reply to the comment]]</a>
                            {/if}
                        </li>
                        {if $numberOfReplies > 0}
                            <li>
                                <a data-toggle="collapse" href="#collapse{$comment.sid}">
                                    [[View all $numberOfReplies Replies]]
                                </a>
                            </li>
                        {/if}
                    </ul>
                    {if $numberOfReplies > 0}
                        <div id="collapse{$comment.sid}" class="collapse">
                            {module name="listing_comments" function="display_comments" script_included="1" results_template="comments.tpl" QUERY_STRING="parent_comment_sid[equal]="|cat:$comment.sid|cat:"&amp;listing_sid[equal]="|cat:$comment.listing_sid}
                        </div>
                    {/if}
                    <div class="replayToCommentFormContainer"></div>
                </div>
            </div>
        </div>
    {/foreach}
</div>
