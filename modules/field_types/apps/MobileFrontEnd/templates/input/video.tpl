{if $hasError}<div class="error validation">{$error}</div>{/if}
{if $value.file_name ne null}
<a  target="VideoPlayer" onclick='javascript:window.open(this.href, "VideoPlayer", "width=300,height=300,top=100")' href="{page_path id='video_player'}?listing_id={$listing.id}">[[Watch a video]]</a>

| <a href="{page_path module='classifieds' function='delete_uploaded_file'}?listing_id={$listing.id}&amp;field_id={$id}">[[Delete]]</a>

<br/><br/>
{/if}
<input type="file" class="inputVideo" name="{$id}" id="{$id}" /><br /><small>[[Supported video formats are: $supportedVideoFormats]]</small>
