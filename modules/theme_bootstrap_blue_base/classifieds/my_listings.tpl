{extension_point name='modules\main\apps\FrontEnd\IMyListingsAdditionRenderer'}
<script>
	$(function() {

		$(".check-all").change(function () {
			$(".massActionForm input[type='checkbox']").prop('checked', $(this).prop('checked'));
		});

		$(".massActionForm input[type='checkbox']").change(function () {
			var atLeastOneCheckboxChecked = $(".massActionForm input[type='checkbox']:checked").length > 0;
			$(".mass-action-controls button[type='submit']").prop('disabled', !atLeastOneCheckboxChecked);
		});

	});
</script>
<div class="myListingsPage">
    <div class="container">
        <div class="searchForm">
            <h1 class="active page-title" title="[[Click to hide the search form:raw]]">[[My Listings]]</h1>
            {module name="classifieds" function="search_form" form_template="my_listings_form.tpl"}
        </div>
        {include file="miscellaneous^toggle_search_form_js.tpl"}
        {display_error_messages}
        {display_success_messages}
        {display_warning_messages}
    </div>
    <div class="space-20"></div>
    <div class="bg-grey">
        <div class="container">
            {assign var="listings_number" value=$listing_search.total_found}
            {if $listing_search.total_found == 0}
            {if empty($search_criteria)}
            {if $REQUEST.userTotalListingNumber == 0}
                {assign var="url" value={page_path id='listing_add'}}
                <p>[[You have no listings now. Click <a href="$url">here</a> to add a new listing.]]</p>
            {else}
                <p>[[There are no listings available that match your search criteria. Please try to broaden your search
                    criteria.]]</p>
            {/if}
            {else}
                <p class="alert alert-danger">[[No listings were found.]]</p>
            {/if}
            {else}
                <form method="get" action="{$GLOBALS.site_url}{$GLOBALS.current_page_uri}" class="massActionForm">
                    <input type="hidden" name="searchId" value="{$listing_search.id}" />
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="h4 found-counter">
                                {assign var="listings_number" value=$listing_search.total_found}
                                [[$listings_number listings found]]
                                <hr class="hidden-sm hidden-md hidden-lg"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="row">
                                {assign  var="restore_url" value="{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&searchId={$listing_search.id}"}
                                <div class="col-sm-8 col-xs-12">
                                    {if !empty($REQUEST.sorting_field_selector_template)}
                                        {$sorting_field_selector_template = $REQUEST.sorting_field_selector_template}
                                    {else}
                                        {$sorting_field_selector_template = "sorting_field_selector.tpl"}
                                    {/if}
                                    {include file=$sorting_field_selector_template listing_search=$listing_search url=$restore_url}
                                </div>

                                <div class="col-sm-4 col-xs-12">
                                    {include file="objects_per_page_selector.tpl" listing_search=$listing_search url=$restore_url}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="space-20"></div>
                            <ul class="mass-action-controls list-inline">
                                <li class="custom-form-control">
                                    <input type="checkbox" id="check-all" class="check-all"/>
                                    <label for="check-all" class="checkbox">&nbsp;</label>
                                </li>
                                <li>
                                    <button type="submit" class="btn btn-warning btn-xs" disabled="disabled" name="action_deactivate" value="Deactivate" onclick="return confirm('[[Are you sure?:raw]]')">
                                        <i class="fa fa-eye-slash"></i> [[Deactivate]]
                                    </button>
                                </li>
                                <li>
                                    <button type="submit" class="btn btn-danger btn-xs" disabled="disabled" name="action_delete" value="Delete" onclick="return confirm('[[Are you sure?:raw]]')">
                                        <i class="fa fa-trash"></i> [[Delete]]
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="searchResultItemsControls listingSearchResultHeader">
                        {include file="miscellaneous^multilevelmenu_js.tpl"}
                    </div>

                    <div class="searchResults mainContentBlock">
                        {foreach from=$listings item=listing}
                            <a name="listing{$listing->getId()}"></a>
                            <div class="itemsSelectorAndSearchResultItemWrapper{if $listing@last} last{/if}">
                                {if $REQUEST.featureActivatedForListing == $listing->getId()}
                                    <div class="featureActivatedNotification">
                                        {capture assign="featureName"}[[{$REQUEST.featureActivated}]]{/capture}
                                        <p>
                                            [[Feature "$featureName" has been activated for this listing]]
                                        </p>
                                        <small>([[click on this box to dismiss]])</small>
                                    </div>
                                {/if}
                                {if $REQUEST.listingActivated == $listing->getId()}
                                    <div class="listingActivatedNotification">
                                        <p>
                                            {if $listing->isActive()}
                                                [[This listing has been activated]]
                                            {else}
                                                {assign var=message value=$listing->getModerationStatus()}
                                                [[Your listing in status $message. It will be activated after approving by administrator.]]
                                            {/if}
                                        </p>
                                        <small>([[click on this box to dismiss]])</small>
                                    </div>
                                {/if}
                                {if $REQUEST.listingDeactivated == $listing->getId()}
                                    <div class="listingDeactivatedNotification alert alert-warning alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <p>
                                            [[This listing has been deactivated]]
                                        </p>
                                    </div>
                                {/if}
                                <div class="searchResultItemWrapper">
                                    {display_listing listing=$listing listingControlsTemplate="my_listing_controls.tpl" listing_search=$listing_search features=$REQUEST.features listingHeaderTemplate="my_listing_header.tpl"}
                                </div>
                            </div>
                        {/foreach}
                    </div>
                </form>
                <div class="space-20"></div>
                {include file="page_selector.tpl" current_page=$listing_search.current_page pages_number=$listing_search.pages_number url=$restore_url}
                <div class="space-20"></div>
                <div class="space-20"></div>
                {require component="jquery" file="jquery.js"}
                <script type="text/javascript">

                    $(document).ready(function () {
                        $(".featureActivatedNotification").click(function () {
                            $(this).remove();
                        });
                        $(".listingActivatedNotification").click(function () {
                            $(this).remove();
                        });
                        $(".listingDeactivatedNotification").click(function () {
                            $(this).remove();
                        });

                        $(".massActionControls ul").hide();

                        $(".massActionForm input[type='checkbox']").change(function () {
                            var atLeastOneCheckboxChecked = $(".massActionForm input[type='checkbox']:checked").length > 0;
                            $(".massActionControls ul").toggle(atLeastOneCheckboxChecked);
                        });

                    });

                </script>
            {/if}
        </div>
    </div>

</div>
{include file="miscellaneous^dialog_window.tpl"}
