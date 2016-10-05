{include file="miscellaneous^dialog_window.tpl"}
<div >
	{$listing_options.listing_options=$product_info}
        <div class="listingOptionsCaption">[[Listing Options]]</div><div class="viewContentsLink"><a href="{page_path module='basket' function='view_listing_options_contents'}?payment_method={$payment_method_class_name}&listing_options={$listing_options|http_build_query}"
	                       onclick="return openDialogWindow('[[Contents:raw]]', this.href, 450)" >[[View Contents]]</a></div>
</div>
