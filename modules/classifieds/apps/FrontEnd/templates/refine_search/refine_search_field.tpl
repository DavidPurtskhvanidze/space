{strip}
	{foreach from=$form_fields item=form_field key=field_name}
		{if $form_field.id == $requiredFieldId}
			{assign var="fieldTemplate" value="classifieds^refine_search/"|cat:$form_field.search_template}
			{if $form_field.type == 'boolean'}
				<li class="option {$form_field.id}">{search property=$form_field.id template=$fieldTemplate}</li>
			{else}
				<li class="tab {$form_field.id}" data-fieldId="{$form_field.id}"><a href="#"><i class="iconMenuTriangle"></i>[[FormFieldCaptions!{$form_field.caption}]]</a></li>
				<li class="content {$form_field.id}">{search property=$form_field.id template=$fieldTemplate}</li>
			{/if}
		{/if}
	{/foreach}
{/strip}
