{strip}
	{$form_field=$form_fields.$requiredFieldId}
	{assign var="fieldTemplate" value="classifieds^refine_search/"|cat:$form_field.search_template}
	{if $form_field.type == 'boolean'}
		<div class="checkbox option {$form_field.id}">
			{search property=$form_field.id template=$fieldTemplate}
		</div>
	{else}
		{capture assign='title'}[[FormFieldCaptions!{$form_field.caption}]]{/capture}
		{capture assign='body'}{search property=$form_field.id template=$fieldTemplate}{/capture}
		{$id=$form_field.id}
		<div class="list-group-item tab {$id}">
			<a data-toggle="collapse" href="#{$id}">
				<span class="glyphicon glyphicon-expand"></span> {$title}
			</a>
			<div id="{$id}" class="collapse">
				{$body}
			</div>
		</div>
	{/if}
{/strip}
