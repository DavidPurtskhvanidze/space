{$listingAddressFields = ['Address','City','State','ZipCode']}
<div class="form inputForm">
	{step title="Listing Information & Location"}
		<table border="0">
			{if $form_fields.Sold and $display_sold_field}
				<tr>
					<td class="sectionHeader Sold" colspan="2"><h3>[[Already Sold?]]</h3></td>
				</tr>
				<tr>
					<td>[[$form_fields.Sold.caption]]{if $form_fields.Sold.is_required}<span class="asterisk">*</span>{/if}</td>
					<td>{input property=Sold}</td>
				</tr>
			{/if}
			<tr>
				<td class="sectionHeader Information" colspan="2"><h3>[[Listing Information]]</h3></td>
			</tr>
			{$excludeAlso = ['Video','ListingRating','feature_youtube_video_id','Description','Sold']}
			{$listingFieldsToExclude = array_merge($listingAddressFields, $excludeAlso, $calendarTypeFieldIds)}
			{foreach from=$form_fields item=fieldInfo}
				{if !in_array($fieldInfo.id, $listingFieldsToExclude)}
					<tr>
						<td class="fieldCaption {$fieldInfo.id}">[[$fieldInfo.caption]] {if $fieldInfo.is_required}<span class="asterisk">*</span>{/if}</td>
						<td class="fieldInput {$fieldInfo.id}">{input property=$fieldInfo.id}</td>
					</tr>
				{/if}
			{/foreach}
			<tr>
				<td class="sectionHeader Address" colspan="2"><h3>[[Address]]</h3></td>
			</tr>
			{foreach from=$listingAddressFields item=fieldName}
				<tr>
					<td class="fieldCaption {$fieldName}">
						{$caption = $form_fields[$fieldName]['caption']}
						[[$caption]]
						{if $form_fields[$fieldName]['is_required']}<span class="asterisk">*</span>{/if}
					</td>
					<td class="fieldInput {$fieldName}">{input property=$fieldName}</td>
				</tr>
			{/foreach}
		</table>
		{include file="miscellaneous^dialog_window.tpl"}
	{/step}
	{foreach from=$calendarTypeFieldIds item=fieldId}
	{$fieldCaption = $form_fields.$fieldId.caption}
	{step title=$fieldCaption}
		<h3 class="sectionHeader {$fieldId}">[[$fieldCaption]] {if $form_fields.$fieldId.is_required}<span class="asterisk">*</span>{/if}:</h3>
		<div class="fieldInput {$fieldId}">{input property=$fieldId}</div>
	{/step}
	{/foreach}
	{step title="Photo & Video"}
		{module name="classifieds" function="manage_pictures" listing_id=$listing.id}
		<table border="0">
			{if $package.video_allowed}
				<tr>
					<td>[[$form_fields.Video.caption]]</td>
					<td>{input property=Video}</td>
				</tr>
			{/if}
			{if $form_fields.feature_youtube_video_id}
				<tr>
					<td>[[$form_fields.feature_youtube_video_id.caption]]</td>
					<td>{input property="feature_youtube_video_id"}</td>
				</tr>
			{/if}
		</table>
	{/step}
	{step title="Description"}
		<h3 class="sectionHeader Description">[[$form_fields.Description.caption]] {if $form_fields.Description.is_required}<span class="asterisk">*</span>{/if}:</h3>
		<div class="fieldInput Description">{input property=Description}</div>
	{/step}
</div>
