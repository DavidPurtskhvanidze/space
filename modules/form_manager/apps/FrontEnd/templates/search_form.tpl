{if $form_info.title}<h1>[[$form_info.title]]</h1>{/if}
{extension_point name='modules\main\apps\FrontEnd\IAdvancedSearchFormAdditionRenderer' categorySid = $category_sid}

<form class="form-horizontal advancedSearchForm" role="form" action="{page_path id='search_results'}">
	<fieldset>
		<input type="hidden" name="action" value="search"/>
		<input type="hidden" name="category_sid[tree][]" value="{$category_sid}"/>
		{foreach from=$fields_to_display item=field_id}
			{if isset($form_fields[$field_id.value])}
				<div class="form-group form-field-{$form_fields[$field_id.value]['type']}">
					<label class="col-sm-2 control-label">[[FormFieldCaptions!{$form_fields[$field_id.value]['caption']}
						]]</label>

					<div class="col-sm-10">{search property=$field_id.value}</div>
				</div>
			{else}
			{if $field_id.value eq 'Fieldset'}
	</fieldset>
				<fieldset class="{$field_id.caption|lcfirst|replace:' ':''}">
					<legend>[[{$field_id.caption}]]</legend>
			{/if}
			{/if}
		{/foreach}
	</fieldset>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">
				[[Search]]
			</button>
		</div>
	</div>
</form>
