{function displayCategories level=0 start=0 limit=null}
	<ul class="list-unstyled">
		{foreach $category.categories as $subcategory}
			{if $subcategory@index < $start}{continue}{/if}
			{if empty($subcategory.categories)}
				<li>
                    <a href="?listing_package_sid={$listing_package_sid}&amp;category_id={$subcategory.id}">
                       <span>[[$subcategory.caption]]</span>
                    </a>
				</li>
			{else}
				<li class="hasChild">
					<span class="notHover">[[$subcategory.caption]]</span>
					{displayCategories category=$subcategory level=$level+1}
				</li>
			{/if}
			{if !is_null($limit) && $limit + $start == $subcategory@iteration}{break}{/if}
		{/foreach}
	</ul>
{/function}
<div class="chooseCategoryPage">
    <div class="container">
        <h1 class="page-title">[[Add Listing]]</h1>
        <div class="space-20"></div>
        <div class="alert bg-info">
            [[Please select a category for your listing from the list below.]]
        </div>
    </div>
    <div class="space-20"></div>
    <div class="bg-grey">
        <div class="space-20"></div>
        <div class="space-20"></div>
        <div class="container">
            <p class="h4 categories-caption">[[Categories]]</p>
            <div class="row">
                <div class="col-sm-11 col-sm-offset-1 col-xs-offset-0 categories">
                    {displayCategories category=$category}
                </div>
            </div>
        </div>
    </div>
</div>
