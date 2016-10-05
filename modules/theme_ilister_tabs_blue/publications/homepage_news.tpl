<div class="newsBlock">
	<h2>[[Stay Tuned]]</h2>
	<a href="{$GLOBALS.site_url}/news" class="readOurNews">[[Read Our News]]</a>
	<ul>
	{foreach from=$articles item=entry name=articles}
		<li class="newsTitle">
			<a href="{page_path id='publications'}{$category.id}/{$entry.id}/{$entry.title|replace:' ':'-'|escape:"urlpathinfo"}.html" class="title">{$entry.title|escape}</a>
			<div class="articleText">
				{$entry.text|cat:""|strip_tags:false|truncate:70:"...":false}
			</div>
		</li>
	{/foreach}
	</ul>
</div>
