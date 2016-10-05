<table class="form">
{foreach from=$form_fields item=form_field}
    {if $form_field.id == 'Video'}
        {if $package.video_allowed}
	        <tr>
	            <td class="fieldCaption">[[$form_field.caption]] {if $form_field.is_required}<span class="asterisk">*</span>{/if}</td>
	            <td>{input property=$form_field.id}</td>
	        </tr>
        {/if}
    {elseif $form_field.id == 'Sold' and $display_sold_field}
        <tr>
	        <td class="fieldCaption">[[$form_field.caption]] {if $form_field.is_required}<span class="asterisk">*</span>{/if}</td>
	        <td>{input property=$form_field.id}</td>
        </tr>
    {elseif $form_field.id != 'ListingRating'}
    <tr>
        <td class="fieldCaption">[[$form_field.caption]] {if $form_field.is_required}<span class="asterisk">*</span>{/if}</td>
        <td>{input property=$form_field.id}</td>
    </tr>
    {/if}
{/foreach}
</table>
{module name="classifieds" function="manage_pictures" listing_id=$listing.id}
