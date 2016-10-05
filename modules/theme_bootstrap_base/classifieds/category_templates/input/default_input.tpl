<div class="form">
	<fieldset>
		{foreach from=$form_fields item=form_field}
			{if $form_field.id == 'Video'}
				{if $package.video_allowed}
					{include file="category_templates/input/display_form_field.tpl" id=$form_field.id center=true}
				{/if}
			{elseif $form_field.id == 'Sold' and $display_sold_field}
				{include file="category_templates/input/display_form_field.tpl" id=$form_field.id center=true}
			{elseif $form_field.id != 'ListingRating'}
				{include file="category_templates/input/display_form_field.tpl" id=$form_field.id center=true}
			{/if}
		{/foreach}
	</fieldset>

	<fieldset>
		<legend>[[Photo]]</legend>
		{module name="classifieds" function="manage_pictures" listing_id=$listing.id}
	</fieldset>
</div>
