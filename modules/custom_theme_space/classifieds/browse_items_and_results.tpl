
    {set_global_parameter key='browsingFieldIds' value= $browsingFieldIds}
    <div class="browsePage">
        <div class="container">
        <ul class="breadcrumb no-padding-left">
            {strip}
                {if $browse_navigation_elements || isset($REQUEST.view_all)}
                    <li><a href="{$GLOBALS.site_url}{$current_page_uri}">[[$TITLE]]</a></li>
                {else}
                    <li class="active">[[$TITLE]]</li>
                {/if}

                {foreach from=$browse_navigation_elements item=element name="nav_elements"}
                    {title}{tr metadata=$element.metadata mode="raw"}{$element.caption}{/tr}{/title}
                    {keywords}{tr metadata=$element.metadata mode="raw"}{$element.caption}{/tr}{/keywords}
                    {description}{tr metadata=$element.metadata mode="raw"}{$element.caption}{/tr}{/description}

                    {if $smarty.foreach.nav_elements.last && !isset($REQUEST.view_all)}
                        <li class="active">{tr metadata=$element.metadata}{$element.caption}{/tr}</li>
                    {else}
                        <li><a href="{$GLOBALS.site_url}{$element.uri}">{tr metadata=$element.metadata}{$element.caption}{/tr}</a></li>
                    {/if}
                {/foreach}
            {/strip}
        </ul>
        {include file="errors.tpl"}
        </div>
        {if $browseItemsGroupedByColumn|@count > 0}
            <div class="container">
                {$browseItems = array()}
                {capture append="browseItems"}
                    {foreach from=$browseItemsGroupedByColumn item=browseItemsForColumn}
                        {foreach from=$browseItemsForColumn item=browseItem}
                            <li class="item">
                                <a href="{$browseItem.url}/"{if $browseItem.count == 0} class="emptyLink"{/if}>[[$browseItem.caption]] ({$browseItem.count})</a>
                            </li>
                        {/foreach}
                    {/foreach}
                {/capture}
                <a href='?view_all' class="btn btn-link">[[View All Listings]] ({$totalListingsNumber})</a>

                <div class="tab-content">
                    <div class="browseItems list{$number_of_cols}Columns tab-pane fade active in">
                        <ul class="list-unstyled browsing">
                            {foreach $browseItems as $item}
                                {$item}
                            {/foreach}
                        </ul>
                    </div>
                </div>
            </div>
        {else}
            {module name="classifieds" function="search_results" results_template=$results_template}
        {/if}
    </div>

