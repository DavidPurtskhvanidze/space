<div class="advancedSearchPage">

<h1>[[Advanced search]]</h1>

<form method="get" action="{page_path id='search_results'}">
	<fieldset>
		<div class="formField formField{$form_fields.keywords.id}">
			<label for="{$form_fields.keywords.id}">[[Search]]</label>
			{search property=$form_fields.keywords.id}
			<small>[[e.g.]] [[73111 Kindle]]</small>
		</div>
		<div class="formField formField{$form_fields.category_sid.id}">
			<label for="{$form_fields.category_sid.id}">[[in]]</label>
			{search property=$form_fields.category_sid.id template="category_tree.tpl"}
		</div>
		<div class="formField formField{$form_fields.pictures.id}">
			<label for="{$form_fields.pictures.id}">[[With pictures only]]</label>
			{search property=$form_fields.pictures.id}
		</div>
		<div class="formField formField{$form_fields.id.id}">
			<label for="{$form_fields.id.id}">[[Ad ID]]</label>
			{search property=$form_fields.id.id}
		</div>
		<div class="formField formField{$form_fields.activation_date.id}">
			<label for="{$form_fields.activation_date.id}">[[Posted within]]</label>
			{search property=$form_fields.activation_date.id}
		</div>
		<div class="formField formField{$form_fields.Address.id}">
			<label for="{$form_fields.Address.id}">[[Address]]</label>
			{search property=$form_fields.Address.id template="string.like.tpl"}
		</div>
		{assign var="fieldsToExclude"
			value=[
					"keywords",
					"pictures",
					"ZipCode",
					"activation_date",
					"Title",
					"Description",
					"Location",
					"Video",
					"sid",
					"id",
					"ListingRating",
					"feature_featured",
					"feature_slideshow",
					"feature_youtube",
					"feature_highlighted",
					"feature_sponsored",
					"feature_youtube_video_id",
					"category_sid",
					"type",
					"moderation_status",
					"package",
					"user",
					"username",
					"active",
					"views",
					"category",
					"Address",
					"Sold"
			]}
		{foreach from=$form_fields item=form_field key=field_name}
			{if !in_array($form_field.id, $fieldsToExclude)}
				<div class="formField formField{$form_field.id}">
					<label for="{$form_field.id}">[[FormFieldCaptions!{$form_field.caption}]]</label>
					{search property=$form_field.id}
				</div>
			{/if}
		{/foreach}
		{if isset($form_fields.Sold)}
			<div class="formField formFieldSold">
				<label for="Sold">[[FormFieldCaptions!Include sold items]]</label>
				{search property=$form_field.id template="include_sold.tpl"}
			</div>
		{/if}
	</fieldset>
	<fieldset class="formControls">
		<input type="hidden" name="action" value="search">
		<input type="submit" value="[[Search:raw]]" />
	</fieldset>
</form>
</div>
