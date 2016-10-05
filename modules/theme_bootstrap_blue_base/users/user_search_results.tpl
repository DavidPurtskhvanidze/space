<div class="bg-grey">
    <div class="container">
        <div class="space-20"></div>
        {display_success_messages}
        <div class="userSearchResultsPage">
            {if $search.total_found > 0}
                <div class="row">
                    {strip}
                        <div class="col-xs-6 vcenter">
                            <div class="h4 found-count">
                                {assign var="record_number" value=$search.total_found}
                                [[$record_number sellers found]]
                            </div>
                        </div>
                        <div class="col-xs-6 vcenter">
                            <div class="row">
                                {assign  var="restore_url" value="{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&searchId={$listing_search.id}"}
                                <div class="col-sm-4 col-sm-offset-8">
                                    {include file="objects_per_page_selector.tpl" listing_search=$search url='?action=restore'}
                                </div>
                            </div>
                        </div>
                    {/strip}
                </div>
                <div class="searchResults">
                    <div class="row">
                        {foreach from=$users item=user}
                            <div class="col-md-4 col-sm-12">
                                {include file="user_search_result_item.tpl"}
                            </div>
                            {if $user@iteration is div by 3}<div class="clearfix visible-md visible-lg"></div>{/if}
                        {/foreach}
                    </div>

                    {include file="miscellaneous^dialog_window.tpl"}
                </div>
                {include file="page_selector.tpl" current_page=$search.current_page pages_number=$search.pages_number url='?action=restore'}
                {include file="miscellaneous^multilevelmenu_js.tpl"}
            {else}
                <p>[[There are no user profiles that match your search criteria.]]</p>
            {/if}
        </div>
    </div>
</div>


