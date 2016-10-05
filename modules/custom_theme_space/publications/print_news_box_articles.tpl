
{if $articles}
    <div class="row">
        {foreach from=$articles item=entry name=articles}
            <div class="col-sm-4 col-xs-12">
                <div class="news-box">
                    <div class="row">
                        <div class="col-sm-12 col-xs-6 col-xxs-12">
                            <div class="news-box-date hidden-sm hidden-xs">
                                {$entry.date|date_format:"%e %B %Y"}
                            </div>
                            <div class="news-box-img text-center">
                                {if isset($entry.picture.thumb.name) && !empty($entry.picture.thumb.name)}
                                    <a href="{page_path id='publications'}{$category.id}/{$entry.id}/{$entry.title|replace:' ':'-'|escape:"urlpathinfo"}.html" class="title wb">
                                        <img class="img-responsive" src="{$entry.picture.thumb.url}" />
                                    </a>
                                {elseif isset($entry.picture_url) && $entry.picture_url|count_characters > 0}
                                    <a href="{page_path id='publications'}{$category.id}/{$entry.id}/{$entry.title|replace:' ':'-'|escape:"urlpathinfo"}.html" class="title wb">
                                        <img class="img-responsive" src="{$entry.picture_url}" />
                                    </a>
                                {/if}
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-6 col-xxs-12">
                            <div class="news-box-date hidden-md hidden-lg">
                                {$entry.date|date_format:"%e %B %Y"}
                            </div>
                            <h3 class="news-box-title">
                                <a href="{page_path id='publications'}{$category.id}/{$entry.id}/{$entry.title|replace:' ':'-'|escape:"urlpathinfo"}.html">
                                    {$entry.title|escape}
                                </a>
                            </h3>
                            <div class="news-box-description">
                                {$entry.description}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {/foreach}
    </div>
{/if}
