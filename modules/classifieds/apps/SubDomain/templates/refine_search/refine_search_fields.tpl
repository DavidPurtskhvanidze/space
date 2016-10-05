<ul class="addCriteriaFields">
	{foreach from=$form_fields item=form_field key=field_name}
		<li>
			<label class="addCriteriaFieldContainer">
				{search property=$form_field.id template="classifieds^refine_search/add_field.tpl"}
				[[FormFieldCaptions!{$form_field.caption}]]
			</label>
		</li>
	{/foreach}
</ul>
