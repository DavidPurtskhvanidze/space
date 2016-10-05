{title}{if $commentSid}[[Reply to The Comment:raw]]{else}[[Add Your Comment:raw]]{/if}{/title}
<div class="addComment">
	<h1>{if $commentSid}[[Reply to The Comment]]{else}[[Add Your Comment]]{/if}</h1>
	{display_error_messages}
	<div class="pageDescription">
		{if $commentSid}
			{capture assign='commentPosted'}{tr type="date"}{$comment.posted}{/tr}{/capture}
			{if $comment.user_sid.value != 0}
				{assign var='commentOwner' value=$comment.user.username}
				<div class="commentInfo">[[You are replying to the comment #$commentSid to listing #$listingSid "$listing".]]</div>
				<div class="commentPosted">[[The comment was posted on $commentPosted by user $commentOwner.]]</div>
			{else}
				<div class="commentInfo">[[You are replying to the comment #$commentSid to listing #$listingSid "$listing".]]</div>
				<div class="commentPosted">[[The comment was posted on $commentPosted by administrator.]]</div>
			{/if}
			<blockquote>
				<div class="commentText">{$comment.comment}</div>
			</blockquote>
		{else}
			<span class="listingInfo">[[You are commenting on listing #$listingSid "$listing"]].</span>
		{/if}
	</div>

	<form action="">
		<fieldset class="formFields">
			<div class="formField formFieldComment">
				<label for="comment">[[Comment]] <span class="asterisk">*</span></label>
				{input property="comment"}
			</div>
		</fieldset>
		<fieldset class="formControls">
			<input type="hidden" name="action" value="add_comment" />
			<input type="hidden" name="listingSid" value="{$listingSid}" />
			<input type="hidden" name="commentSid" value="{$commentSid}" />
			<input type="hidden" name="returnBackUri" value="{$returnBackUri}" />
			<div><input type="submit" value="{if $commentSid}[[Add your reply:raw]]{else}[[Add your comment:raw]]{/if}" class="button" /></div>
		</fieldset>
	</form>
</div>
