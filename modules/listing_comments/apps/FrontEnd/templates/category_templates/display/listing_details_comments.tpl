{assign var="numberOfComments" value=$listing.numberOfComments.value}
{assign var='current_uri' value=$GLOBALS.current_page_uri}

{if $GLOBALS.current_user.logged_in}
	{$addCommentLink = "<a id='0' class='showAddCommentForm' href='{page_path id='comment_add'}?listingSid={$listing.sid}&amp;returnBackUri={$current_uri}'>[[Add your comment.]]</a>"}
{else}
	{$addCommentLink = "<a href='{page_path id='comment_add'}?returnBackUri={$current_uri}&amp;restoreActiveTab=restore&amp;commentSid=0'>[[Add your comment.]]</a>"}
{/if}
<div class="comments">
	<h2>[[Comments]]</h2>
	{if $numberOfComments == 0}
		[[There are no comments yet.]] {$addCommentLink}
	{else}
		<a href="{page_path id='comments'}{$listing.sid}/">[[View all $numberOfComments comments]]</a> | {$addCommentLink}
	{/if}
	<div class="addCommentFormContainer"></div>
	{module name="listing_comments" function="display_comments" limit=3 results_template="comments.tpl" QUERY_STRING="listing_sid[equal]="|cat:$listing.sid}
</div>
<script type="text/javascript">
	$(function () {
		$(".showReplayToCommentForm").click(function (e) {
			e.preventDefault();
			var replayToCommentFormContainer = $(this).nextAll(".replayToCommentFormContainer");
			$.get($(this).attr("href"), { raw_output: true}, function(data){
				displayForm(data, replayToCommentFormContainer);
			});
		});

		$(".showAddCommentForm").click(function (e) {
			e.preventDefault();
			var addCommentFormContainer = $(this).nextAll(".addCommentFormContainer");
			$.get($(this).attr("href"), { raw_output: true}, function(data){
				displayForm(data, addCommentFormContainer);
			});
		});

		$('.comments').on('click', ".cancelAddComment", function(e){
			e.preventDefault();
			$(this).parents('.addComment').remove();
		});

		{if !is_null($smarty.get.commentSid)}//After logging in we go to the add comment form by finding the anchor
			var commentSid = {$smarty.get.commentSid};
			$(document).scrollTop($("#" + commentSid).offset().top);//Scrolling to anchor
			$("#" + commentSid).click();//Open form
		{/if}
	});

	function displayForm(data, container) {
		var title;
		var comment;
		//backing up entered data
		if(($(".addComment [name='comment']")).length>0)
		{
            title = $(".addComment [name='title']").val();
            comment = CKEDITOR.instances["comment"].getData();
			$(".addComment").remove();
		}
		container.append(data); //Add Form to container div
		//Passing backed up data
		$(".addComment [name='title']").val(title);
		CKEDITOR.instances["comment"].setData(comment);
	}
</script>
{require component='ckeditor' file='ckeditor.js'}
