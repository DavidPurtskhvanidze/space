{foreach from=$listings item=listing}
	<item>
		<title>{$listing|cat:""|strip_tags:false|escape:"html"}</title>
		<link>{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html</link>
		<description>
			<![CDATA[
			{if $listing.pictures.numberOfItems > 0}{listing_image pictureInfo=$listing.pictures.collection.0 thumbnail=1}{/if}
			{$listing.Description.value|truncate:200|escape_user_input}
			]]>
		</description>
		<guid>{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html</guid>
	</item>
{/foreach}
