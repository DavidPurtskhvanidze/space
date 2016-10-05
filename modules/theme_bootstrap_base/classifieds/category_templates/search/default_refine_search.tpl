{function name='display_list_group_item'}
	<div class="list-group-item tab {$id}">
		<a data-toggle="collapse" href="#{$id}">
			<span class="glyphicon glyphicon-expand"></span> {$title}
		</a>
		<div id="{$id}" class="collapse">
			{$body}
		</div>
	</div>
{/function}

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<a data-toggle="collapse" href="#ManageSearch">
				<span class="glyphicon glyphicon-collapse-down"></span> [[Manage Search]]
			</a>
		</h3>
	</div>
	{include file="classifieds^search_controls.tpl"}
</div>

<div class="refineSearchForm">
	<form method="get" action="{page_path id='search_results'}" role="form" class="refine-search-form">
		<input type="hidden" name="raw_output" value="true">
		<input type="hidden" name="searchId" value="{$listing_search.id}">
		<input type="hidden" name="action" value="refine">
		<input type="hidden" name="view_all" value="1">

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">[[Refine Search]]</h3>
			</div>

			<div class="list-group">
				{$dontDisplay = $ignoreFieldIds|array_merge:$additionalCriteria}
				{if 'keywords'|in_array:$dontDisplay === false}
					{capture assign='title'}{tr domain="FormFieldCaptions"}{$form_fields.keywords.caption}{/tr}{/capture}
					{capture assign='body'}{search property='keywords' template='classifieds^refine_search/keywords.tpl'}{/capture}
					{display_list_group_item title=$title body=$body id='Keywords'}
				{/if}
				{if 'State'|in_array:$dontDisplay === false}
					{capture assign='title'}{tr domain="FormFieldCaptions"}{$form_fields.State.caption}{/tr}{/capture}
					{capture assign='body'}{search property='State'}{/capture}
					{display_list_group_item title=$title body=$body id='State'}
				{/if}

				{if 'category_sid'|in_array:$dontDisplay === false}
					{capture assign='title'}[[FormFieldCaptions!Category]]{/capture}
					{capture assign='body'}{search property=$form_fields.category_sid.id template='classifieds^refine_search/category_tree.tpl'}{/capture}
					{display_list_group_item title=$title body=$body id='Category'}
				{/if}

				{foreach from=$form_fields item=form_field}
					{if $form_field.id|in_array:$additionalCriteria && $form_field.type != 'boolean'}
						{assign var="fieldTemplate" value="classifieds^refine_search/"|cat:$form_field.search_template}

						{capture assign='title'}[[FormFieldCaptions!{$form_field.caption}]]{/capture}
						{capture assign='body'}{search property=$form_field.id template=$fieldTemplate}{/capture}
						{display_list_group_item title=$title body=$body id=$form_field.id}
					{/if}
				{/foreach}

				{capture assign='title'}{tr domain="FormFieldCaptions"}[[Options]]{/tr}{/capture}
				{capture assign='body'}
					<div class="more-less">
						<div class="items">
							{foreach from=$form_fields item=form_field}
								{if $form_field.id|in_array:$additionalCriteria && $form_field.type == 'boolean'}
									{$fieldTemplate = "classifieds^refine_search/"|cat:$form_field.search_template}
									<div class="checkbox option {$form_field.id}">
										{search property=$form_field.id template=$fieldTemplate}
									</div>
								{/if}
							{/foreach}
						</div>
						<a href="#" class="show-more">[[Show More Options]]</a>
						<a href="#" class="show-less">[[Hide Options]]</a>
					</div>
				{/capture}
				{display_list_group_item title=$title body=$body id='Options'}

				<div class="list-group-item header AddMoreCriteria">
					<a href="#">[[Add/Remove Criteria:raw]]</a>
				</div>
				<div class="list-group-item">
					<a href="#" class="ResetSearchCriteria">[[Reset Search Criteria]]</a>
				</div>
			</div>
		</div>
	</form>
</div>

{require component="jquery" file="jquery.js"}
{require component="jquery-ui" file="jquery-ui.js"}
{require component="js" file="more-less.js"}
{require component="js" file="more-less.css"}

<script type="text/javascript">
	var resetObserver = [];// Will be used to reset Refine Search Form
	var onFormSubmitObserver = []; //Will be used to perform some actions on ajax form submitting
	var formFields = [
			'State',
			{strip}
			{foreach $autoOptions as $autoOption}
				,'{$autoOption}'
			{/foreach}
			{foreach from=$additionalCriteria item=fieldId}
				,'{$fieldId}'
			{/foreach}
			{/strip}
		];

	$(function () {
		$('.collapse')
				.on('show.bs.collapse', function () {
					$('.glyphicon', $(this).parent())
							.addClass('glyphicon-collapse-down')
							.removeClass('glyphicon-expand');
				})
				.on('hide.bs.collapse', function () {
					$('.glyphicon', $(this).parent())
							.addClass('glyphicon-expand')
							.removeClass('glyphicon-collapse-down');
				})
	})
</script>
{require component="js" file="TreeController.js"}
{include file="classifieds^category_templates/search/refine_search_js.tpl"}
