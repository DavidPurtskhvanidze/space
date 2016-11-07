<script>
    $(function() {
        $(".select-all").change(function () {
            $(".massActionForm input[type='checkbox'].item-selector").prop('checked', $(this).prop('checked'));
        });

        $(".massActionForm input[type='checkbox']").change(function () {
            var atLeastOneCheckboxChecked = $(".massActionForm input[type='checkbox'].item-selector:checked").length > 0;
            $(".mass-action-controls button[type='submit']").prop('disabled', !atLeastOneCheckboxChecked);
        });
    });
</script>
{capture assign="restore_url"}
    {$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&amp;searchId={$listing_search.id}
{/capture}
<div class="container">
    <div class="savedListingsPage">
        <h1 class="title">[[Saved Listings]]</h1>
        {if $listing_search.total_found == 0}
            <p class="error">[[You have no saved listings now.]]</p>
        {else}
            {assign var="listings_number" value=$listing_search.total_found}

            <form method="post" action="" class="massActionForm">
                {CSRF_token}
                <input type="hidden" name="searchId" value="{$listing_search.id}" />


                <div class="search-results-header">
                    <div class="row">
                        <div class="col-md-5 col-sm-6 search-results-header-ls">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-6">
                                    <div class="search-results-header-item">
                                        {include file="objects_per_page_selector.tpl" listing_search=$listing_search url=$restore_url}
                                    </div>
                                </div>
                                <div class="col-md-8 col-sm-8 col-xs-6">
                                    <div class="search-results-header-item">
                                        {if !empty($REQUEST.sorting_field_selector_template)}
                                            {$sorting_field_selector_template = $REQUEST.sorting_field_selector_template}
                                        {else}
                                            {$sorting_field_selector_template = "sorting_field_selector.tpl"}
                                        {/if}
                                        {include file=$sorting_field_selector_template listing_search=$listing_search url=$restore_url}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7 col-sm-6 search-results-header-rs hidden-xs">
                            <div class="row">
                                <div class="col-md-4 hidden-sm">
                                    {assign var="listings_number" value=$listing_search.total_found}
                                    [[$listings_number listings found]]
                                </div>
                                <div class="col-md-8">
                                    <ul class="mass-action-controls list-inline">
                                        <li>
                                            <button type="submit" class="btn btn-danger btn-xs" disabled="disabled" name="action_delete" value="Delete" onclick="return confirm('[[Are you sure?:raw]]')">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i> [[Delete]]
                                            </button>
                                        </li>
                                        <li>
                                            <label>
                                                <input data-toggle="tooltip" title="All" type="checkbox" class="select-all"/>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="searchResults mainContentBlock">
                    {foreach from=$listings item=listing name=listings_block}
                        {display_listing listing=$listing listingControlsTemplate="saved_listing_controls.tpl" listing_search=$listing_search}
                    {/foreach}
                </div>
                {include file="page_selector.tpl" current_page=$listing_search.current_page pages_number=$listing_search.pages_number url=$restore_url}
            </form>
            {require component="jquery" file="jquery.js"}
            <script type="text/javascript">
                $(document).ready(function () {
                    $(".massActionControls ul").hide();

                    $(".massActionControls a").click(function () {
                        window.location.href = $(this).attr("href") + "&" + $(".massActionForm").serialize();
                        return false;
                    });

                    $(".massActionControls .checkAll").change(function () {
                        $(".massActionForm .itemsSelector input[type='checkbox']").prop("checked", $(this).prop("checked")).trigger('change');
                    });

                    $(".massActionForm .itemsSelector input[type='checkbox']").change(function () {
                        var atLeastOneCheckboxChecked = $(".massActionForm .itemsSelector input[type='checkbox']:checked").length > 0;
                        $(".massActionControls ul").toggle(atLeastOneCheckboxChecked);
                    });
                    $(".massActionForm .listingControls input[type='checkbox']:checked").each(function(){
                        onCompleteActionComparison($(this));
                    });
                });
                function onCompleteActionComparison($checkbox)
                {
                    var listingId = $checkbox.val();
                    if ($checkbox.prop('checked'))
                    {
                        $('.listingControls[data-listingId='+listingId+'] .compareListingsDelim').show();
                        $('.listingControls[data-listingId='+listingId+'] .compareListingsLink').show();
                    }
                    else
                    {
                        $('.listingControls[data-listingId='+listingId+'] .compareListingsDelim').hide();
                        $('.listingControls[data-listingId='+listingId+'] .compareListingsLink').hide();
                    }
                }
            </script>
        {/if}
    </div>
</div>