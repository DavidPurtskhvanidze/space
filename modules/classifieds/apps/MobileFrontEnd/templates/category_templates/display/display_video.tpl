{if $listing.feature_youtube_video_id.exists && $listing.feature_youtube_video_id.isNotEmpty}
	<div class="youtube-video-container">
		{module name="listing_feature_youtube" function="display_youtube" listing=$listing width="800" height="450"}
	</div>
{/if}

{if $listing.Video.uploaded}
	<li>
		<a onclick='return openDialogWindow("[[Watch a video]]", this.href, 1087, true)' href="{page_path id='video_player'}?listing_id={$listing.id}&raw_output=1">[[Watch a video]]</a>
	</li>
{/if}

