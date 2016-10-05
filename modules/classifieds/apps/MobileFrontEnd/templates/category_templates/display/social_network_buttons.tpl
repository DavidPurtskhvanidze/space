<div class="socialNetworkLikeButtonWrapper">
{capture assign='listingUrl'}{page_url id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
	<div class="twitter">
		<a href="http://twitter.com/share" class="twitter-share-button" data-url="{$listingUrl}" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	</div>
	<div class="facebook">
		<iframe src="http://www.facebook.com/plugins/like.php?href={$listingUrl|urlencode}&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:115px; height:24px;" allowTransparency="true"></iframe>
	</div>
</div>
