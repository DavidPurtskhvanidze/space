{function displayCategories level=0 start=0 limit=null}
	<ul>
		{foreach $category.categories as $subcategory}
			{if $subcategory@index < $start}{continue}{/if}
			{if empty($subcategory.categories)}
				<li>
					<a href="?listing_package_sid={$listing_package_sid}&amp;category_id={$subcategory.id}">[[$subcategory.caption]]</a>
				</li>
			{else}
				<li>
					[[$subcategory.caption]]
					{displayCategories category=$subcategory level=$level+1}
				</li>
			{/if}
			{if !is_null($limit) && $limit + $start == $subcategory@iteration}{break}{/if}
		{/foreach}
	</ul>
{/function}

<div class="chooseCategoryPage">
	<h1 class="addListingHeader">[[Add Listing]]</h1>
	[[Please select a category for your listing from the list below.]]

	<div class="categoriesList">
		{displayCategories category=$category}
	</div>
</div>
