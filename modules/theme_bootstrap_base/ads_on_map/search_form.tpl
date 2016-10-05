<div class="adsOnMap searchForm">
	<form class="form-inline pull-left" role="form">
		<div class="form-group">
			<label for="">[[Search]]</label>
			{search property=$form_fields.keywords.id}
		</div>
		<div class="form-group">
			{search property=$form_fields.category_sid.id template="ads_on_map^category_templates/search/category_tree_noredirect.tpl"}
		</div>
		{if isset($form_fields.Zip)}
			{search property=$form_fields.Zip.id template="ads_on_map^category_templates/search/map.tpl"}
		{else}
			{search property=$form_fields.ZipCode.id template="ads_on_map^category_templates/search/map.tpl"}
		{/if}
		<input type="hidden" name="action" value="search"/>

		<button type="submit" class="btn btn-default">[[Find:raw]]</button>
	</form>
	<div class="dropdown pull-right">
		<a id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="/page.html" class="btn btn-primary">
			[[Manage Search]] <span class="caret"></span>
		</a>


		<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
			<li>
				<a href="{page_path id='user_saved_searches'}">[[View Saved Searches]]</a>
			</li>
			<li>
				<a href="{page_path id='user_saved_listings'}">[[View Saved Ads]]</a>
			</li>
			<li>
				<a onclick='if (listingsInComparisonCounter >= 2) javascript:window.open(this.href, "_blank"); else alert("[[Please add 2 or more listings for comparison.:raw]]"); return false;'
				   href="{page_path id='compared_listings'}">[[Compare Selected Listings]]</a>
			</li>
		</ul>
	</div>
</div>
