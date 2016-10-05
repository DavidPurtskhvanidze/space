{$listingAddressFields = ['Address','City','State','ZipCode']}

{step title="Listing Information"}
{if $form_fields.Sold and $display_sold_field}
	<fieldset>
		<legend>[[Already Sold?]]</legend>
		{include file="category_templates/input/display_form_field.tpl" id='Sold' center=true}
	</fieldset>
{/if}
	<fieldset>
		<legend>[[Listing Information]]</legend>
		{$excludeAlso = ['Video','ListingRating','feature_youtube_video_id','Description','Sold','Price']}
		{$listingFieldsToExclude = array_merge($listingAddressFields, $excludeAlso, $calendarTypeFieldIds)}
		{foreach from=$form_fields item=fieldInfo}
			{if !in_array($fieldInfo.id, $listingFieldsToExclude)}
				{include file="category_templates/input/display_form_field.tpl" id=$fieldInfo.id center=true}
			{/if}
		{/foreach}
					{if isset($form_fields.Price.caption) && !empty($form_fields.Price.caption)}
							{capture assign="PricePlaceholder"}[[$form_fields.Price.caption]] {$GLOBALS.custom_settings.listing_currency}{/capture}
							{include file="category_templates/input/display_form_field.tpl" id='Price' placeholder=$PricePlaceholder center=true}
					{/if}
	</fieldset>
	<fieldset>
		<legend>[[Address]]</legend>
		{foreach from=$listingAddressFields item=fieldName}
			{include file="category_templates/input/display_form_field.tpl" id=$fieldName center=true}
		{/foreach}
	</fieldset>
	{include file="miscellaneous^dialog_window.tpl"}
{/step}

{foreach from=$calendarTypeFieldIds item=fieldId}
	{$fieldCaption = $form_fields.$fieldId.caption}
	{step title=$fieldCaption}
		<fieldset>
			<legend>[[$fieldCaption]]</legend>
			{include file="category_templates/input/display_form_field.tpl" id=$fieldId}
		</fieldset>
	{/step}
{/foreach}

{step title="Photo & Video"}
	<fieldset>
		<legend>[[Photo]]</legend>
		{module name="classifieds" function="manage_pictures" listing_id=$listing.id}
	</fieldset>
	<fieldset>
		<legend>[[Video]]</legend>
		{if $package.video_allowed}
			{include file="category_templates/input/display_form_field.tpl" id='Video'}
		{/if}
		{if $form_fields.feature_youtube_video_id}
			{include file="category_templates/input/display_form_field.tpl" id='feature_youtube_video_id'}
		{/if}
	</fieldset>
{/step}

{step title="Description"}
	<fieldset>
		<legend>[[Description]]</legend>
		{include file="category_templates/input/display_form_field.tpl" id='Description'}
	</fieldset>
{/step}
