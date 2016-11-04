<section class="add-listing-block">
    <h1 class="title">
        [[Add Listing]]
    </h1>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="add-listing-box">
                <div class="steps-wrap">
                    <ul class="list-unstyled">
                        <li class="active first-step">
                            <span class="add-listing-step-title">
                                <a href="/~david/ilister-7-5-0-RC2/listing/add/?listing_package_sid=8">
                                    Change Category
                                </a>
                            </span>
                            <span class="add-listing-step active"></span>
                            <span class="add-listing-step-line disabled"></span>
                        </li>
                        <li disabled="disabled" class="disabled">
							<span class="add-listing-step-title">
							    <a href="#">Listing Information</a>
							</span>
                            <span class="add-listing-step"></span>
                            <span class="add-listing-step-line disabled"></span>
                        </li>
                        <li class="disabled">
							<span class="add-listing-step-title">
                                <a href="#">Photo &amp; Video</a>
							</span>
                            <span class="add-listing-step disabled"></span>
                            <span class="add-listing-step-line disabled"></span>
                        </li>
                        <li class="disabled last-step">
							<span class="add-listing-step-title">
                                <a href="#">Description</a>
                            </span>
                            <span class="add-listing-step disabled"></span>
                            <span class="add-listing-step-line disabled"></span>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
                <hr class="visible-xs-block">
                <div class="masonry">
                    {function displayCategories level=0 start=0 limit=null}
                        <ul class="categoryes-list-choose">
                            {foreach $category.categories as $subcategory}
                                {if $subcategory@index < $start}{continue}{/if}
                                {if empty($subcategory.categories)}
                                    <li>
                                        <a href="?listing_package_sid={$listing_package_sid}&amp;category_id={$subcategory.id}">
                                            <span>[[$subcategory.caption]]</span>
                                        </a>
                                    </li>
                                {else}
                                    <li class="hasChild masonry-item">
                                        <span class="notHover">[[$subcategory.caption]]</span>
                                        {displayCategories category=$subcategory level=$level+1}
                                    </li>
                                {/if}
                                {if !is_null($limit) && $limit + $start == $subcategory@iteration}{break}{/if}
                            {/foreach}
                        </ul>
                    {/function}
                    {displayCategories category=$category}
                </div>
                <script>
                    $('.masonry').children('ul').children('li').addClass('masonry-item');
                </script>
            </div>
        </div>
    </div>
</section>

