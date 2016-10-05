{if !$REQUEST.script_included}
{require component="jquery" file="jquery.js"}
<script type="text/javascript">
	var siteUrl = "{$GLOBALS.site_url}";
	var displayCommentPageUri = "{page_uri id='comments'}";
	function showPermanentLink(listingId, commentId)
	{
		prompt("[[Please copy the permanent link of this comment to your clipboard]]", siteUrl + displayCommentPageUri + listingId + "/#comment" + commentId);
	}
	$(document).ready(function(){
		$("a.toggleReplies").click(function(){
			$(this).next("div.replies").toggle("blind");
			return false;
		})

		var hash = window.location.hash;
		var isValidHtmlId = (hash.charAt(1).match(/[A-Za-z]/) !== null);
		if (hash.length > 0 && isValidHtmlId)
		{
			$(hash).parents(".replies").toggle();
			$(window.location).attr('hash', hash);
		}
	});
	
	function expandCollapseMenuOnHover(menuSwitchSelector, menuSelector) {
		this.menuSwitch = $(menuSwitchSelector);
		this.menu = $(menuSelector);
		this.timer = false;

		this.fnExpandMenu = function() {
			if (this.timer) {
				window.clearTimeout(this.timer);
				this.timer = false;
			}
			else {
				this.menuSwitch.toggleClass("expanded");
				this.menu.show();
				this.menu.mouseenter({ obj: this}, function(event) {
					if (event.data.obj.timer) {
						window.clearTimeout(event.data.obj.timer);
						event.data.obj.timer = false;
					}
				});
				this.menu.mouseleave({ obj: this}, function(event) {
					event.data.obj.fnCollapseMenu();
				})
			}
			return false;
		}
		this.fnCollapseMenu = function() {
			$.proxy(
				this.timer = window.setTimeout(
					$.proxy(function() {
						this.timer = false;
						this.menu.hide();
						this.menuSwitch.toggleClass("expanded");
						this.menu.unbind('mouseenter');
						this.menu.unbind('mouseleave');
					},this),
					250
				),
				this
			);
			return false;
		}
			
		this._init = function() {
			if (this.menuSwitch.length == 0 || this.menu.length == 0) {
				return false;
			}

			this.menu.css({
				position: "absolute"
			});
			
			this.menuSwitch.mouseenter({ obj: this}, function(event) {
				event.data.obj.fnExpandMenu();
				return false;
			});
			this.menuSwitch.mouseleave({ obj: this}, function(event) {
				event.data.obj.fnCollapseMenu();
				return false;
			})
		}
		
		this._init();
	}
</script>
{/if}

{assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
{assign var='current_uri' value=$current_uri|urlencode}

{foreach from=$comments item=comment}
<div class="comment {if $comment.published.isFalse}notPublishedComment{/if}"{if $comment.published.isFalse} style="background:{get_custom_setting id='hidden_comments_background_color' theme=$GLOBALS.current_theme}"{/if}>
	<a name="comment{$comment.sid}" id="comment{$comment.sid}"></a>
	{if $comment.published.isFalse}<div class="hiddenCommentTooltip">[[This comment has not been published and is currently visible only to its author and the owner of the listing.]]</div>{/if}
	<div class="userPicture actionMenu{$comment.sid}">
		<a href="#">
			{if $comment.user.ProfilePicture.exists && $comment.user.ProfilePicture.ProfilePicture.name}
				<img src="{$comment.user.ProfilePicture.ProfilePicture.url}" />
			{else}
				<img src="{url file='main^user.png'}" alt="[[No photos:raw]]" />
			{/if}
		</a>
		<div class="userAndCommentActions" style="display: none;">
			<ul>
				{if $comment.user.isNotEmpty}
					<li>
						{$username = $comment.user.username.value}
						<a href="{page_path id='users'}{$comment.user.id}/{if $comment.user.DealershipName.exists}{$comment.user.DealershipName.value|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{/if}{$comment.user.FirstName.value|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$comment.user.LastName.value|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[View $username's Profile]]</a>
					</li>
					<li>
						<a href="{page_path id='users_contact'}{$comment.user.id}/{if $comment.user.DealershipName.exists}{$comment.user.DealershipName.value|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{/if}{$comment.user.FirstName.value|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$comment.user.LastName.value|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html?listing_sid={$comment.listing_sid}&amp;returnBackUri={$current_uri}%26restoreActiveTab%3Drestore">[[Contact User]]</a>
					</li>
				{else}
					<li>
						<a href="{page_path id='contact'}?returnBackUri={$current_uri}%26restoreActiveTab%3Drestore">[[Contact Admin]]</a>
					</li>
				{/if}
				<li>
					<a href="{page_path module='miscellaneous' function='report_improper_content'}?objectType=comment&amp;objectId={$comment.sid}&amp;returnBackUri={$current_uri}%26restoreActiveTab%3Drestore">[[Report This]]</a>
				</li>
				<li>
					<a href="#" onclick="showPermanentLink({$comment.listing_sid}, {$comment.id});return false;">[[Permanent Link]]</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="commentAuthor">
		<span class="commentAuthorUsername">{if $comment.user.isNotEmpty}{$comment.user.username.value}{else}[[Admin]]{/if}</span>
		<span class="commentAuthorSays">[[says]]:</span>
	</div>
	<div class="commentRating">
		{include file="miscellaneous^rating.tpl" rating=$comment.ListingRating}
	</div>
	<div class="commentPostedDate">[[$comment.posted]]</div>
	<div class="commentText">{$comment.comment}</div>
	<div class="commentActions">
		{assign var="numberOfReplies" value=$comment.numberOfReplies.value}
		{if $GLOBALS.current_user.logged_in}
			<a id='{$comment.id}' data-comment-sid='{$comment.id}' class='showReplayToCommentForm' href='{page_path id='comment_add'}?listingSid={$comment.listing_sid}&amp;returnBackUri={$current_uri}&amp;commentSid={$comment.id}'>[[Reply to the comment]]</a>
		{else}
			<a id='{$comment.id}' data-comment-sid='{$comment.id}' href='{page_path id='comment_add'}?listingSid={$comment.listing_sid}&amp;returnBackUri={$current_uri}&amp;restoreActiveTab=restore&amp;commentSid={$comment.id}'>[[Reply to the comment]]</a>
		{/if}
		{if $numberOfReplies > 0}
		| <a href="#" class="toggleReplies">[[View all $numberOfReplies Replies]]</a>
		<div style="display:none" class="replies">{module name="listing_comments" function="display_comments" script_included="1" results_template="comments.tpl" QUERY_STRING="parent_comment_sid[equal]="|cat:$comment.sid|cat:"&amp;listing_sid[equal]="|cat:$comment.listing_sid}</div>
		{/if}
		<div class="replayToCommentFormContainer"></div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		new expandCollapseMenuOnHover(".actionMenu{$comment.sid} a", ".actionMenu{$comment.sid} .userAndCommentActions");
	});
</script>
{/foreach}
