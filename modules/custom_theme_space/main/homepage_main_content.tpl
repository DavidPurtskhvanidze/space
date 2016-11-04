{extends 'homepage_main_content_base.tpl'}

{block name="listing_feature"}
    {module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="classifieds" function="search_form" form_template="quick_search.tpl" do_not_modify_meta_data=true}
    {module name="listing_feature_featured" function="featured_listings" featured_listings_template="featured_listings.tpl" number_of_rows="1" number_of_cols="4" do_not_modify_meta_data=true}
{/block}
{block name="browse_block"}
    <div class="browse-block">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#BrowseByCategory" role="tab" data-toggle="tab">[[Browse by Category]]</a>
            </li>
            <li>
                <a href="#BrowseByState" role="tab" data-toggle="tab">[[Browse by State]]</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade in active" id="BrowseByCategory">
                {module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="classifieds" function="browse" category_id="root" fields="category_sid" number_of_cols="4" browse_template="browse_by_categories_and_subcategories.tpl" do_not_modify_meta_data=true}
            </div>
            <div class="tab-pane fade" id="BrowseByState">
                {module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="classifieds" function="browse" category_id="root" fields="State" number_of_cols="4" browse_template="browse_by_state.tpl" do_not_modify_meta_data=true}
            </div>
        </div>
    </div>
{/block}
{block name="recent_listings"}
	{module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='3H' name="recent_listings" function="display_recent_listings" number_of_rows="1" number_of_cols="4" do_not_modify_meta_data=true}
{/block}
