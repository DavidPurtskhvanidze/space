{function displayCategories level=0 start=0 limit=null}
	<ul class="nav">
		{foreach $category.categories as $subcategory}
			{if $subcategory@index < $start}{continue}{/if}
			{if empty($subcategory.categories)}
				<li>
                    <a href="?listing_package_sid={$listing_package_sid}&amp;category_id={$subcategory.id}">
                       <span class="nav-tabs">[[$subcategory.caption]]</span>
                    </a>
				</li>
			{else}
				<li class="hasChild">
					<span class="nav-tabs notHover">[[$subcategory.caption]]</span>
					{displayCategories category=$subcategory level=$level+1}
				</li>
			{/if}
			{if !is_null($limit) && $limit + $start == $subcategory@iteration}{break}{/if}
		{/foreach}
	</ul>
{/function}

<div class="chooseCategoryPage">
    <h1>[[Add Listing]]</h1>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="thumbnail categories">
                <div class="alert alert-warning">
                    [[Please select a category for your listing from the list below.]]
                </div>

                <div class="categoriesList">
                    {displayCategories category=$category}
                </div>
            </div>
        </div>
    </div>

</div>
