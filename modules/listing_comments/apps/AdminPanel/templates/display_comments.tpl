{assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
{assign var='current_uri' value=$current_uri|urlencode}

<div class="comments">
    {foreach from=$comments item=comment name=commentsBlock}
        <div class="margin-10-0" {if $comment.published.isFalse}style="background:{get_custom_setting id='hidden_comments_background_color' theme=$GLOBALS.current_theme}"{/if} data-item-sid="{$comment.sid}">
            <div class="row">
                    <div class="row pull-padding-0">
                        <div class="col-md-9">
                            <div class="dropdown">
                                <label>
                                    <input class="ace" type="checkbox" name="selectedCommets[]" value="{$comment.sid}" id="checkbox_{$smarty.foreach.commentsBlock.iteration}"{if isset($selectedCommets) && in_array($comment.sid, $selectedCommets)} checked="CHECKED"{/if}/>
                                    <span class="lbl"></span>
                                </label>
                                <span class="text-info">
                                    {if $comment.user.isNotEmpty}{$comment.user.username.value}{else}[[Admin]]{/if}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <div class="btn-group">
                                {capture assign="returnBackUri"}{page_uri module='listing_comments' function='manage_comments'}?action=restore&amp;searchId={$searchId}{/capture}
                                <a class="itemControls reply btn btn-xs btn-default" onclick='return openDialogWindow("[[Reply to comment:raw]]", this.href, 500, true)' href="{page_path module='listing_comments' function='add_listing_comment'}?listingSid={$comment.listing_sid}&commentSid={$comment.sid}&returnBackUri={$returnBackUri|urlencode}" title="[[Reply]]">
                                     <i class="icon-reply bigger-120"></i>
                                </a>
                                {if $comment.published.isTrue}
	                                <a class="itemControls hide-comment btn btn-xs btn-yellow" href="{page_path module='listing_comments' function='comment_actions'}?action=Hide&selectedCommets[0]={$comment.sid}&searchId={$searchId}" title="[[Hide]]">
                                         <i class="icon-ban-circle"></i>
                                    </a>
                                {else}
                                    <a class="itemControls publish btn btn-xs btn-success" href="{page_path module='listing_comments' function='comment_actions'}?action=Publish&selectedCommets[0]={$comment.sid}&searchId={$searchId}" title="[[Publish]]">
                                        <i class="icon-check"></i>
                                    </a>
                                {/if}
                                <a class="itemControls edit btn btn-xs btn-info" onclick='return openDialogWindow("[[Edit comment:raw]]", this.href, 500, true)' href="{page_path module='listing_comments' function='edit_listing_comment'}?commentSid={$comment.sid}&searchId={$searchId}" title="[[Edit]]">
                                    <i class="icon-edit bigger-120"></i>
                                </a>
                                <a class="itemControls delete btn btn-xs btn-danger" onclick="return confirm('[[Are you sure you want to delete this comment?:raw]]')" href="{page_path module='listing_comments' function='comment_actions'}?action=Delete&selectedCommets[0]={$comment.sid}&searchId={$searchId}" title="[[Delete]]">
                                    <i class="icon-remove bigger-120"></i>
                                </a>
                                {if !$comment.user.isEmpty}
                                    {if $comment.user.trusted_user.isTrue}
                                        <a class="itemControls makeUserUntrusted btn btn-xs btn-warning" href="{page_path module='listing_comments' function='comment_actions'}?action=Make+User+Untrusted&selectedCommets[0]={$comment.sid}&searchId={$searchId}" title="[[Make the User Untrusted]]">
                                            <i class="icon-circle-blank"></i>
                                        </a>
                                    {else}
                                        <a class="itemControls makeUserTrusted btn btn-xs btn-warning" href="{page_path module='listing_comments' function='comment_actions'}?action=Make+User+Trusted&selectedCommets[0]={$comment.sid}&searchId={$searchId}" title="[[Make the User Trusted]]">
                                            <i class="icon-circle"></i>
                                        </a>
                                    {/if}
                                {/if}
                                {module name="listing_comments" function="display_listing_comment_controls" commentSid=$comment.sid searchId=$searchId}
                            </div>
                        </div>
                    </div>
                    <p>
                        <div class="comment-text">
		                    {$comment.comment}
		                </div>
	                    {if $comment.published.isFalse}
                             <p class="text-danger hiddenCommentTooltip">[[This comment has not been published and is currently visible only to its author and the owner of the listing.]]</p>
                        {/if}
                    </p>
                    <small>[[$comment.posted]]</small>

                    {assign var="numberOfReplies" value=$comment.numberOfReplies.value}
                    {if $numberOfReplies > 0}
                        <a data-toggle="collaps" data-comment-sid="{$comment.sid}" class="pull-right replies btn btn-default" href="#collapse{$comment.sid}">
                            [[View all $numberOfReplies Replies]]
                        </a>
                    {/if}
            </div>
            {if $numberOfReplies > 0}
                <div id="collapse{$comment.sid}" class="collapse margin-left-30">
                    {module name="listing_comments" function="display_listing_comments" script_included="1" results_template="display_comments.tpl" QUERY_STRING="parent_comment_sid[equal]="|cat:$comment.sid|cat:"&amp;listing_sid[equal]="|cat:$comment.listing_sid}
                </div>
            {/if}
            <div class="replayToCommentFormContainer"></div>
        </div>
    {/foreach}
</div>
