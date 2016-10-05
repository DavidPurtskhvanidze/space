<div class="browseByStateAndCategories">
	{if $browse_level > 1 and $browseItemsGroupedByColumn|@count > 0}
		<div class="browseByStateWrapper">
			{module name="classifieds" function="browse" category_id="root" fields="State" number_of_cols="1" browse_template="browse_by_state.tpl" inheritRequest=false selectedItem=$browse_navigation_elements.0.caption}
		</div>
		<div class="centerBlockWrapper">
			{module name="listing_feature_featured" function="featured_listings" featured_listings_template="featured_listings.tpl" number_of_rows="1" number_of_cols="4"}
			<div class="browseByCategories list{$number_of_cols}Columns">
				<ul>
					{foreach from=$browseItemsGroupedByColumn item=browseItemsForColumn}
						<li>
							<ul>
								{foreach from=$browseItemsForColumn item=browseItem}
									<li>
										<h3>
											<a href="{$browseItem.url}/?view_all"{if $browseItem.count == 0} class="emptyLink"{/if}>[[$browseItem.caption]]  <span class="listingNumber">({$browseItem.count})</span></a>
										</h3>
										{module name="classifieds" function="browse" category_id="{$browseItem.category_id}" fields="State, category_sid" browse_template="browse_by_state_categories_and_subcategory.tpl" number_of_cols="1"}
									</li>
								{/foreach}
							</ul>
						</li>
					{/foreach}
				</ul>
			</div>
		</div>
	{else}
		{module name="listing_feature_featured" function="featured_listings" featured_listings_template="featured_listings.tpl" number_of_rows="1" number_of_cols="6"}
		{if $browseItemsGroupedByColumn|@count > 0}
			{include file="browse_by_state.tpl"}
		{else}
			{module name="classifieds" function="search_results" results_template=$results_template}
		{/if}
	{/if}
</div>
