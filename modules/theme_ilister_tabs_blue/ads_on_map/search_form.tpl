<div class="searchForm">
	<form>
        <ul>
            <li>
                [[Search]] {search property=$form_fields.keywords.id}{search property=$form_fields.category_sid.id template="ads_on_map^category_templates/search/category_tree_noredirect.tpl"}
            </li>     
            <li>
                <input type="hidden" name="action" value="search" />
                {if isset($form_fields.Zip)}{search property=$form_fields.Zip.id template="ads_on_map^category_templates/search/map.tpl"}
                {else}{search property=$form_fields.ZipCode.id template="ads_on_map^category_templates/search/map.tpl"}{/if}
                <input type="submit" class="button" value="[[Find:raw]]"/>
            </li>
			<li>
				<ul class="searchOnMapControls multilevelMenu">
					<li>
						<a class="caption" href="#">[[Manage Search]]</a>
						<ul>
							<li>
								<a href="{page_path id='user_saved_searches'}">[[View Saved Searches]]</a>
							</li>
							<li>
								<a href="{page_path id='user_saved_listings'}">[[View Saved Ads]]</a>
							</li>
							<li>
								<a onclick='if (listingsInComparisonCounter >= 2) javascript:window.open(this.href, "_blank"); else alert("[[Please add 2 or more listings for comparison.:raw]]"); return false;' href="{page_path id='compared_listings'}">[[Compare Selected Listings]]</a>
							</li>
						</ul>
					</li>
				</ul>
			</li>
        </ul>
	</form>
</div>

