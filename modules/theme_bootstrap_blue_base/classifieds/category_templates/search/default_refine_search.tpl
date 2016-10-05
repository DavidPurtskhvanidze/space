{function name='display_list_group_item'}
    <div class="list-group-item tab {$id}">
        <a data-toggle="collapse" href="#{$id}" onclick="CollapseIconChange(this)">
            {$title} <i class="fa collapse-icon fa-chevron-down pull-right"></i>
        </a>
        <div id="{$id}" class="collapse {$class}">
            {$body}
        </div>
    </div>
{/function}
<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-blue">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a data-toggle="collapse" href="#ManageSearch" onclick="CollapseIconChange(this)">
                        [[Manage Search]] <i class="fa collapse-icon fa-chevron-down pull-right"></i>
                    </a>
                </h3>
            </div>
            {include file="classifieds^search_controls.tpl"}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="refineSearchForm">
            <form method="get" action="{page_path id='search_results'}" role="form" class="refine-search-form">
                <input type="hidden" name="raw_output" value="true">
                <input type="hidden" name="searchId" value="{$listing_search.id}">
                <input type="hidden" name="action" value="refine">
                <input type="hidden" name="view_all" value="1">

                <div class="panel panel-blue">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a href="#refineSearch" data-toggle="collapse" onclick="CollapseIconChange(this)">
                                [[Refine Search]] <i class="fa collapse-icon fa-chevron-down pull-right"></i>
                            </a>
                        </h3>
                    </div>

                    <div id="refineSearch" class="list-group collapse">
                        {$dontDisplay = $ignoreFieldIds|array_merge:$additionalCriteria}
                        {if 'keywords'|in_array:$dontDisplay === false}
                            {capture assign='title'}{tr domain="FormFieldCaptions"}{$form_fields.keywords.caption}{/tr}{/capture}
                            {capture assign='body'}{search property='keywords' template='classifieds^refine_search/keywords.tpl'}{/capture}
                            {display_list_group_item title=$title body=$body id='Keywords' class='rf-form'}
                        {/if}
                        {if 'State'|in_array:$dontDisplay === false}
                            {capture assign='title'}{tr domain="FormFieldCaptions"}{$form_fields.State.caption}{/tr}{/capture}
                            {capture assign='body'}{search property='State'}{/capture}
                            {display_list_group_item title=$title body=$body id='State' class='rf-form'}
                        {/if}

                        {if 'category_sid'|in_array:$dontDisplay === false}
                            {capture assign='title'}[[FormFieldCaptions!Category]]{/capture}
                            {capture assign='body'}{search property=$form_fields.category_sid.id template='classifieds^refine_search/category_tree.tpl'}{/capture}
                            {display_list_group_item title=$title body=$body id='Category' class='rf-form'}
                        {/if}

                        {foreach from=$form_fields item=form_field}
                            {if $form_field.id|in_array:$additionalCriteria && $form_field.type != 'boolean'}
                                {assign var="fieldTemplate" value="classifieds^refine_search/"|cat:$form_field.search_template}

                                {capture assign='title'}[[FormFieldCaptions!{$form_field.caption}]]{/capture}
                                {capture assign='body'}{search property=$form_field.id template=$fieldTemplate}{/capture}
                                {display_list_group_item title=$title body=$body id=$form_field.id class='rf-form'}
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
                                                {search property=$form_field.id template=$fieldTemplate class='rf-form'}
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
    </div>
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

    function CollapseIconChange(element) {
        $(element).children("i").toggleClass("fa-chevron-down").toggleClass("fa-chevron-up");
    }
</script>
{require component="js" file="TreeController.js"}
{include file="classifieds^category_templates/search/refine_search_js.tpl"}
