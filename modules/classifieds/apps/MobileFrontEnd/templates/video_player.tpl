{if $listing.Video.status ne 'uploaded'}
	<div class="warning alert">[[Video processing in]]</div>
{else}
    <div class="embed-responsive embed-responsive-16by9">
        <object class="embed-responsive-item" type="application/x-shockwave-flash" data="http://www.youtube.com/v/{$listing.Video.video_id}&amp;fs=1&amp;rel=0">
            <param name="movie" value="http://www.youtube.com/v/{$listing.Video.video_id}&amp;fs=1&amp;rel=0" />
            <param name="allowFullScreen" value="true" />
            <param name="allowscriptaccess" value="always" />
            <param name="wmode" value="opaque" />
        </object>
    </div>
{/if}
