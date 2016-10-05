<div class="browseByCategories list{$number_of_cols}Columns">
	<h2>[[Browse by Category]]</h2>
	{if $browseItemsGroupedByColumn|@count > 0}
		<ul>
			{foreach from=$browseItemsGroupedByColumn item=browseItemsForColumn}
				<li>
					<ul>
						{foreach from=$browseItemsForColumn item=browseItem}
							<li>
								<h3>
									<a href="{page_path id='browse'}{$browseItem.url}/?view_all"{if $browseItem.count == 0} class="emptyLink"{/if}>[[$browseItem.caption]]  <span class="listingNumber">({$browseItem.count})</span></a>
								</h3>
								{module name="classifieds" function="browse" category_id="{$browseItem.category_id}" fields="category_sid" browse_template="browse_by_subcategory.tpl" number_of_cols="1"}
							</li>
						{/foreach}
					</ul>
				</li>
			{/foreach}
		</ul>
	{/if}
</div>

