<div class="advancedSearchForm">
<h1>[[Advanced Search]]</h1>
{extension_point name='modules\main\apps\FrontEnd\IAdvancedSearchFormAdditionRenderer' categorySid = $category_sid}
<form method="get" action="{page_path id='search_results'}">
    <div><input type="hidden" name="action" value="search" /></div>
    <table class="form">
        <tr>
            <td>[[Search]]</td>
			<td>
				{if $GLOBALS.settings.autocomplete_enable_in_keyword_search}
					{search property=$form_fields.keywords.id template="string_with_autocomplete.tpl" parameters=['element_id_prefix'=>'defaultSearch','preselection_fields'=>['category_sid']]}
				{else}
					{search property=$form_fields.keywords.id}
				{/if}
				[[in]] {search property=$form_fields.category_sid.id template="category_tree.tpl"}</td>
        </tr>
        <tr>
            <td>[[Address]]</td>
            <td>{search property=$form_fields.Address.id}</td>
        </tr>
        <tr>
            <td>[[City]]</td>
            <td>{search property=$form_fields.City.id}</td>
        </tr>
        <tr>
            <td>[[State]]</td>
            <td>{search property=$form_fields.State.id}</td>
        </tr>
        <tr>
            <td>[[Search Within]]</td>
            <td>{search property=$form_fields.ZipCode.id}</td>
        </tr>
        <tr>
            <td>[[Posted within]]</td>
            <td>{search property=activation_date}</td>
        </tr>
        <tr>
            <td>[[With pictures only]]</td>
            <td>{search property=$form_fields.pictures.id}</td>
        </tr>
		{assign var="fieldsToExclude"
			value=[
					"keywords",
					"pictures",
					"Address",
					"City",
					"State",
					"ZipCode",
					"activation_date",
					"Title",
					"Description",
					"Location",
					"Video",
					"sid",
					"id",
					"feature_featured",
					"feature_slideshow",
					"feature_youtube",
					"feature_highlighted",
					"feature_sponsored",
					"feature_youtube_video_id",
					"auto_extend",
					"category_sid",
					"type",
					"moderation_status",
					"package",
					"user",
					"username",
					"active",
					"views",
					"category",
					"Sold"
			]}
        {foreach from=$form_fields item=form_field key=field_name}
        	{if !in_array($form_field.id, $fieldsToExclude)}
				<tr>
					<td>[[FormFieldCaptions!{$form_field.caption}]] <span class="fieldCaption {$form_field.type}"></span></td>
					<td>{search property=$form_field.id}</td>
				</tr>
			{/if}
        {/foreach}
		{if isset($form_fields.Sold)}
			<tr>
				<td>[[FormFieldCaptions!Include sold items]]</td>
				<td>{search property="Sold" template="include_sold.tpl"}</td>
			</tr>
		{/if}
        <tr>
            <td></td>
            <td><div><input type="submit" value="[[Search:raw]]" class="button" /></div></td>
        </tr>
    </table>
</form>
</div>
