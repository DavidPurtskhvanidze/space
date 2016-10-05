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
<div class="savedListingsPage">
    <div class="container">
        <h1 class="page-title">[[Saved Listings]]</h1>
    </div>
	<div class="search-result-container">
        <div class="container">
            {if $listing_search.total_found == 0}
                <p class="alert alert-danger">[[You have no saved listings now.]]</p>
            {else}
                {assign var="listings_number" value=$listing_search.total_found}

                <form method="post" action="" class="massActionForm">
                    {CSRF_token}
                    <input type="hidden" name="searchId" value="{$listing_search.id}" />
                    <div class="row">
                        {capture assign="restore_url"}
                            {$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&amp;searchId={$listing_search.id}
                        {/capture}
                        <div class="col-xs-12 col-sm-6">
                            <div class="found-count">
                                {assign var="listings_number" value=$listing_search.total_found}
                                <span class="h5">[[$listings_number found]]</span>
                                <hr class="hidden-sm hidden-md hidden-lg"/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="row">
                                {assign  var="restore_url" value="{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&searchId={$listing_search.id}"}
                                <div class="col-sm-8">
                                    {if !empty($REQUEST.sorting_field_selector_template)}
                                        {$sorting_field_selector_template = $REQUEST.sorting_field_selector_template}
                                    {else}
                                        {$sorting_field_selector_template = "classifieds^sorting_field_selector.tpl"}
                                    {/if}
                                    {include file=$sorting_field_selector_template listing_search=$listing_search url=$restore_url}
                                </div>

                                <div class="col-sm-4">
                                    {include file="classifieds^objects_per_page_selector.tpl" listing_search=$listing_search url=$restore_url}
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-12">
                            <div class="space-20"></div>

                            <ul class="mass-action-controls list-inline">
                                <li class="custom-form-control">
                                    <input type="checkbox" id="select-all" class="select-all"/>
                                    <label for="select-all" class="checkbox">&nbsp;</label>
                                </li>
                                <li>
                                    <button type="submit" class="btn btn-danger btn-xs" disabled="disabled" name="action_delete" value="Delete">
                                        <span class="glyphicon glyphicon-remove"></span> [[Delete]]
                                    </button>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <hr/>
                    <div class="searchResults mainContentBlock">
                        {foreach from=$listings item=listing name=listings_block}
                            <div class="searchResultItemWrapper">
                                {display_listing listing=$listing listingControlsTemplate="saved_listing_controls.tpl" listing_search=$listing_search listingHeaderTemplate="saved_listing_header.tpl"}
                            </div>
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
</div>
