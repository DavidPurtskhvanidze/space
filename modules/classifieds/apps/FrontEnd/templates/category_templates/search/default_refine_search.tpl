<div class="refineSearchForm">
	<form method="get" action="{page_path id='search_results'}">
		<input type="hidden" name="raw_output" value="true">
		<input type="hidden" name="searchId" value="{$listing_search.id}">
		<input type="hidden" name="action" value="refine">
        <input type="hidden" name="view_all" value="1">
		<div class="refineSearchHeader"><span>[[Refine Search]]</span></div>
		<ul class="menu accordion">
			<li class="tab ManageSearch opened"><a href="#"><i class="iconMenuTriangle"></i>[[Manage Search]]</a></li>
			<li class="content ManageSearch">
				{include file="classifieds^search_controls.tpl"}
			</li>

			<li class="header ResetSearchCriteria"><a href="#">[[Reset Search Criteria]]</a></li>

			{$dontDisplay = $ignoreFieldIds|array_merge:$additionalCriteria}
			{if 'keywords'|in_array:$dontDisplay === false}
				<li class="tab Keywords"><a href="#"><i class="iconMenuTriangle"></i>[[FormFieldCaptions!Keywords]]</a></li>
				<li class="content Keywords">{search property='keywords' template='classifieds^refine_search/keywords.tpl'}</li>
			{/if}
			{if 'category_sid'|in_array:$dontDisplay === false}
				<li class="tab Category"><a href="#"><i class="iconMenuTriangle"></i>[[FormFieldCaptions!Category]]</a></li>
				<li class="content Category">{search property=$form_fields.category_sid.id template='classifieds^refine_search/category_tree.tpl'}</li>
			{/if}
			{if 'State'|in_array:$dontDisplay === false}
				<li class="tab State"><a href="#"><i class="iconMenuTriangle"></i>[[FormFieldCaptions!State]]</a></li>
				<li class="content State">{search property='State'}</li>
			{/if}

			{foreach from=$form_fields item=form_field}
				{if $form_field.id|in_array:$additionalCriteria && $form_field.type != 'boolean'}
					{assign var="fieldTemplate" value="classifieds^refine_search/"|cat:$form_field.search_template}
					<li class="tab {$form_field.id}"><a href="#"><i class="iconMenuTriangle"></i>[[FormFieldCaptions!{$form_field.caption}]]</a></li>
					<li class="content {$form_field.id}">{search property=$form_field.id template=$fieldTemplate}</li>
				{/if}
			{/foreach}

			<li class="tab Options"><a href="#"><i class="iconMenuTriangle"></i>[[Options]]</a></li>
			<li class="content Options">
				<ul>
					{foreach from=$form_fields item=form_field}
						{if $form_field.id|in_array:$additionalCriteria && $form_field.type == 'boolean'}
							{$fieldTemplate = "classifieds^refine_search/"|cat:$form_field.search_template}
							<li class="option {$form_field.id}">
								{search property=$form_field.id template=$fieldTemplate}
							</li>
						{/if}
					{/foreach}
				</ul>
				<div class="optionDisplayModeSwitch expand"><a href="#">[[Show More Options]]</a></div>
				<div class="optionDisplayModeSwitch collapse"><a href="#">[[Hide Options]]</a></div>
			</li>
			<li class="header AddMoreCriteria">
				<a title="[[Add/Remove Criteria:raw]]" href="#">[[Add/Remove Criteria:raw]]</a>
				<div class="addMoreCriteriaPopUp" title="[[Add/Remove Criteria:raw]]"></div>
			</li>
		</ul>
	</form>
</div>
<script type="text/javascript">
	var resetObserver = [];// Will be used to reset Refine Search Form
	var onFormSubmitObserver = []; //Will be used to perform some actions on ajax form submitting
	var formFields = [
		'State'
		{strip}
		{foreach from=$additionalCriteria item=fieldId}
			,'{$fieldId}'
		{/foreach}
		{/strip}
	];
</script>
{require component="js" file="TreeController.js"}
{include file="classifieds^category_templates/search/refine_search_js.tpl"}
