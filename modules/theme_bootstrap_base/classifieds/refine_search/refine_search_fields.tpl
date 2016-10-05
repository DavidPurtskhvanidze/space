<form class="form-horizontal addCriteriaFields">
	<div class="row">
		{foreach from=$form_fields item=form_field key=field_name}
			<div class="col-sm-6">
				<div class="checkbox option {$autoOption}">
					<label>
						{search property=$form_field.id template="classifieds^refine_search/add_field.tpl"}
						[[FormFieldCaptions!{$form_field.caption}]]
					</label>
				</div>
			</div>
		{/foreach}
	</div>
</form>
