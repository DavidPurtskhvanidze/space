{title}{$category.title}{/title}
<div class="newsPage">
	<h1>[[PhrasesInTemplates!{$category.title}]]</h1>
	{foreach from=$articles item=entry name=articles}
		{if $smarty.foreach.articles.first}<dl>{/if}
		<div class="item">
			{if isset($entry.picture.thumb.name) && !empty($entry.picture.thumb.name)}
				<div class="picture">
					<a href="{page_path id='publications'}{$category.id}/{$entry.id}/{$entry.title|replace:' ':'-'|escape:"urlpathinfo"}.html" class="title">
						<img src="{$entry.picture.thumb.url}" />
				    </a>
				</div>
            {elseif isset($entry.picture_url) && $entry.picture_url|count_characters > 0}
                <div class="picture">
                    <a href="{page_path id='publications'}{$category.id}/{$entry.id}/{$entry.title|replace:' ':'-'|escape:"urlpathinfo"}.html" class="title">
                        <img src="{$entry.picture_url}" />
                    </a>
                </div>
		    {/if}
			<div>
				<dt><a href="{page_path id='publications'}{$category.id}/{$entry.id}/{$entry.title|replace:' ':'-'|escape:"urlpathinfo"}.html" class="title">{$entry.title|escape}</a></dt>
				<dd>{$entry.description}</dd>
			</div>
			<div class="clearfix"></div>
		</div>
		{if $smarty.foreach.articles.last}</dl>{/if}
	{foreachelse}
		[[There are no articles available at this time]]
	{/foreach}
</div>
