{assign var="categoryTitle" value=$category.title}
{title}[[$categoryTitle]]{/title}
<div class="newsBlock">
	<h2>[[$categoryTitle]]</h2>
	{if $articles}
		<ul>
		{foreach from=$articles item=entry name=articles}
			<li class="newsTitle">
				<a href="{page_path id='publications'}{$category.id}/{$entry.id}/{$entry.title|replace:' ':'-'|escape:"urlpathinfo"}.html" class="title">{$entry.title|escape}</a>
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
