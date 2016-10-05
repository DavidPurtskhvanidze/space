[[The following options were added to the basket for the <a href="#listing$listingSid">listing #$listingSid "$listingCaption"</a>]]:
<ul>
	{foreach from=$options item="option"}
		<li>[[$option.name]]</li>
	{/foreach}
</ul>
<div class="viewMyBasketLink">
    <a href="{page_path id='basket'}">[[View My Basket]]</a>
</div>
