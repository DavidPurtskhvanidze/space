{assign var="categoryTitle" value=$category.title}
<div class="news-block widget gallery-view">
	<h2 class="h2">[[$categoryTitle]]</h2>
	{if $articles}
		<div class="row">
            {foreach from=$articles item=entry name=articles}
                <div class="col-sm-6 col-md-3">
                    <div class="item">
                        <p class="date grey-text">{$entry.date|date_format:"%d-%m %Y"}</p>
                        <div class="text-center">
                            {if isset($entry.picture.thumb.name) && !empty($entry.picture.thumb.name)}
                                <a href="{page_path id='publications'}{$category.id}/{$entry.id}/{$entry.title|replace:' ':'-'|escape:"urlpathinfo"}.html" class="title">
                                    <img src="{$entry.picture.thumb.url}" />
                                </a>
                            {elseif isset($entry.picture_url) && $entry.picture_url|count_characters > 0}
                                <a href="{page_path id='publications'}{$category.id}/{$entry.id}/{$entry.title|replace:' ':'-'|escape:"urlpathinfo"}.html" class="title">
                                    <img src="{$entry.picture_url}" />
                                </a>
                            {/if}
                        </div>
                        <h3 class="title">
                            <a class="h4" href="{page_path id='publications'}{$category.id}/{$entry.id}/{$entry.title|replace:' ':'-'|escape:"urlpathinfo"}.html">{$entry.title|escape}</a>
                        </h3>
                        <div class="description">
                            {$entry.description}
                        </div>
                        <div class="more">
                            <a href="{page_path id='publications'}{$category.id}/{$entry.id}/{$entry.title|replace:' ':'-'|escape:"urlpathinfo"}.html" class="btn btn-orange h6">
                                [[more]]
                            </a>
                        </div>
                    </div>
                </div>
                {if $entry@iteration is div by 4}<div class="clearfix visible-md visible-lg"></div>{/if}
                {if $entry@iteration is div by 2}<div class="clearfix visible-sm"></div>{/if}
            {/foreach}
		</div>
	{/if}
</div>
