[[The following options were successfully enabled and will be activated on the <a href="#listing$listingSid">listing #$listingSid "$listingCaption"</a> activation]]:
<ul>
	{foreach from=$options item="option"}
		<li>[[$option.name]]</li>
	{/foreach}
</ul>
