{if $actionDone}
	<script type="text/javascript">
		this.location.href = "{$GLOBALS.site_url}{$returnBackUri}";
	</script>
{else}
	<div class="row addCommentForm">
		<div class="col-sm-12">
			<h4>{if $commentSid}[[Reply to The Comment]]{else}[[Add a comment]]{/if}</h4>
			{if $commentSid}
				{capture assign='commentPosted'}{tr type="date"}{$comment.posted}{/tr}{/capture}
				<div class="well">
				{if !empty($comment.user_sid.value)}
					<p class="text-info">[[You are replying to the comment #{$commentSid} to listing #{$listingSid} "{$listing}".]]</p>
					<p class="text-default"><small>[[The comment was posted on {$commentPosted} by user {$comment.user.username}.]]</small></p>
					{else}
					<p class="text-info">[[You are replying to the comment #{$commentSid} to listing #{$listingSid} "{$listing}".]]</p>
					<p class="text-default"><small>[[The comment was posted on {$commentPosted} by administrator.]]</small></p>
				{/if}
				</div>
				<blockquote>
					<div class="commentText">{$comment.comment}</div>
				</blockquote>
				{else}
				<span class="text-info margin-10-0 block">[[You are commenting on listing #{$listingSid} "{$listing}".]]</span>
			{/if}

			{display_error_messages}

		</div>
		<div class="col-sm-12">
			<div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-40"></i>) are mandatory]]</div>

			<form action="" class="form form-horizontal">
				<input type="hidden" name="action" value="add_comment">
				<input type="hidden" name="listingSid" value="{$listingSid}">
				<input type="hidden" name="commentSid" value="{$commentSid}">
				<input type="hidden" name="returnBackUri" value="{$returnBackUri}">
				<div class="form-group">
					<label class="control-label col-sm-3">[[Comment]] <i class="icon-asterisk smaller-40"></i></label>
					<div class="col-sm-9">{input property="comment"}</div>
				</div>
				<div class="clearfix form-actions"><input type="submit" value="[[Add a Comment:raw]]" class="btn btn-default"></div>
			</form>
		</div>
	</div>
{/if}
