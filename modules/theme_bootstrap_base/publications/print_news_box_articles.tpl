{assign var="categoryTitle" value=$category.title}
{title}[[$categoryTitle]]{/title}
<div class="newsBlock">
	<h2>[[$categoryTitle]]</h2>
	{if $articles}
		<ul class="list-unstyled">
		{foreach from=$articles item=entry name=articles}
			<li class="item">
				<a href="{page_path id='publications'}{$category.id}/{$entry.id}/{$entry.title|replace:' ':'-'|escape:"urlpathinfo"}.html" class="title">{$entry.title|escape}</a>
				<div class="articleText">
					{$entry.text|cat:""|strip_tags:false|truncate:100:"...":false}
				</div>
			</li>
		{/foreach}
		</ul>
	{else}
		<p class="noNewsNotification">[[There are no articles available at this time]]</p>
	{/if}
	<p class="newsArchive">
		<a href="{$GLOBALS.site_url}/news">[[Archive]]</a>
	</p>
</div>
