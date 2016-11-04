{function name='display_list_group_item'}
    <div class="refineSearchForm-field {$id}">
        <label class="control-label">{$title}</label>
        {$body}
    </div>
{/function}
<div class="search-result-bar">
    <div class="row">
        <div class="col-xs-6">
            <div class="wb">
                <div data-toggle="TabButton" class="ManageSearchTab"><span class="thin-caret"></span> [[Manage Search]]</div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="wb">
                <div data-toggle="TabButton" class="RefineSearchTab"><span class="thin-caret"></span> [[Refine Search]]</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div id="ManageSearchTab" class="search-result-header-panel">
                {include file="classifieds^search_controls.tpl"}
            </div>
        </div>
        <div class="col-xs-12">
            <div id="RefineSearchTab" class="search-result-header-panel">
                <div id="RefineSearch">
                    <div class="refineSearchForm">
                        <form method="get" action="{page_path id='search_results'}" role="form" class="refine-search-form">
                            <input type="hidden" name="raw_output" value="true">
                            <input type="hidden" name="searchId" value="{$listing_search.id}">
                            <input type="hidden" name="action" value="refine">
                            <input type="hidden" name="view_all" value="1">

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
                            <div class="header AddMoreCriteria">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a id="AddMoreCriteriaID" class="text-center default-button wb" href="#">[[Add/Remove Criteria:raw]]</a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#" class="ResetSearchCriteria text-center default-button wb">[[Reset Search Criteria]]</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('[data-toggle="TabButton"]').click(function() {
        var className = $(this).attr('class');
        switch (className) {
            case 'ManageSearchTab':
                var current = $("#"+className);
                    if (current.hasClass('active-tab')) {
                        current.removeClass('active-tab');
                        $(this).parent().css('outline' , 'none');
                    }else{
                        $(this).parent().css('outline' , '1px solid #cccccc');
                        $(current).addClass('active-tab');
                        $("#RefineSearchTab").removeClass('active-tab');
                        $(".RefineSearchTab").parent().css('outline' , 'none');
                    }
                break;
            case 'RefineSearchTab':
                var current = $("#"+className);
                if (current.hasClass('active-tab')) {
                    $(current).removeClass('active-tab');
                    $(this).parent().css('outline' , 'none');
                }else{
                    $(this).parent().css('outline' , '1px solid #cccccc');
                    $(current).addClass('active-tab');
                    $("#ManageSearchTab").removeClass('active-tab');
                    $(".ManageSearchTab").parent().css('outline' , 'none');
                }
                break;
        }
    });
</script>
{*<div class="row">*}
    {*<div class="col-xs-6">*}
        {*<div class="panel panel-default">*}
            {*<div class="panel-heading">*}
                {*<h3 class="panel-title">*}
                    {*<a id="ManageSearchID" data-toggle="collapse" href="#ManageSearch" aria-expanded="true" aria-controls="ManageSearch">*}
                        {*<span class="thin-caret"></span> [[Manage Search]]*}
                    {*</a>*}
                {*</h3>*}
            {*</div>*}
        {*</div>*}
    {*</div>*}
    {*<div class="col-xs-6">*}
        {*<div class="panel panel-default">*}
            {*<div class="panel-heading">*}
                {*<h3 class="panel-title">*}
                    {*<a id="RefineSearchID" data-toggle="collapse" href="#RefineSearch" aria-expanded="true" aria-controls="RefineSearch">*}
                        {*<span class="thin-caret"></span> [[Refine Search]]*}
                    {*</a>*}
                {*</h3>*}
            {*</div>*}
        {*</div>*}
    {*</div>*}
    {*<div class="col-xs-12">*}
        {*{include file="classifieds^search_controls.tpl"}*}
    {*</div>*}
    {*<div class="col-xs-12">*}
        {*<div id="RefineSearch" class="list-group collapse">*}
            {*<div class="refineSearchForm">*}
                {*<form method="get" action="{page_path id='search_results'}" role="form" class="refine-search-form">*}
                {*<input type="hidden" name="raw_output" value="true">*}
                {*<input type="hidden" name="searchId" value="{$listing_search.id}">*}
                {*<input type="hidden" name="action" value="refine">*}
                {*<input type="hidden" name="view_all" value="1">*}

                    {*{$dontDisplay = $ignoreFieldIds|array_merge:$additionalCriteria}*}
                    {*{if 'keywords'|in_array:$dontDisplay === false}*}
                        {*{capture assign='title'}{tr domain="FormFieldCaptions"}{$form_fields.keywords.caption}{/tr}{/capture}*}
                        {*{capture assign='body'}{search property='keywords' template='classifieds^refine_search/keywords.tpl'}{/capture}*}
                        {*{display_list_group_item title=$title body=$body id='Keywords'}*}
                    {*{/if}*}
                    {*{if 'State'|in_array:$dontDisplay === false}*}
                        {*{capture assign='title'}{tr domain="FormFieldCaptions"}{$form_fields.State.caption}{/tr}{/capture}*}
                        {*{capture assign='body'}{search property='State'}{/capture}*}
                        {*{display_list_group_item title=$title body=$body id='State'}*}
                    {*{/if}*}

                    {*{if 'category_sid'|in_array:$dontDisplay === false}*}
                        {*{capture assign='title'}[[FormFieldCaptions!Category]]{/capture}*}
                        {*{capture assign='body'}{search property=$form_fields.category_sid.id template='classifieds^refine_search/category_tree.tpl'}{/capture}*}
                        {*{display_list_group_item title=$title body=$body id='Category'}*}
                    {*{/if}*}

                    {*{foreach from=$form_fields item=form_field}*}
                        {*{if $form_field.id|in_array:$additionalCriteria && $form_field.type != 'boolean'}*}
                            {*{assign var="fieldTemplate" value="classifieds^refine_search/"|cat:$form_field.search_template}*}

                            {*{capture assign='title'}[[FormFieldCaptions!{$form_field.caption}]]{/capture}*}
                            {*{capture assign='body'}{search property=$form_field.id template=$fieldTemplate}{/capture}*}
                            {*{display_list_group_item title=$title body=$body id=$form_field.id}*}
                        {*{/if}*}
                    {*{/foreach}*}

                    {*{capture assign='title'}{tr domain="FormFieldCaptions"}[[Options]]{/tr}{/capture}*}
                    {*{capture assign='body'}*}
                        {*<div class="more-less">*}
                            {*<div class="items">*}
                                {*{foreach from=$form_fields item=form_field}*}
                                    {*{if $form_field.id|in_array:$additionalCriteria && $form_field.type == 'boolean'}*}
                                        {*{$fieldTemplate = "classifieds^refine_search/"|cat:$form_field.search_template}*}
                                        {*<div class="checkbox option {$form_field.id}">*}
                                            {*{search property=$form_field.id template=$fieldTemplate}*}
                                        {*</div>*}
                                    {*{/if}*}
                                {*{/foreach}*}
                            {*</div>*}
                            {*<a href="#" class="show-more">[[Show More Options]]</a>*}
                            {*<a href="#" class="show-less">[[Hide Options]]</a>*}
                        {*</div>*}
                    {*{/capture}*}
                    {*{display_list_group_item title=$title body=$body id='Options'}*}

                    {*<div class="list-group-item header AddMoreCriteria">*}
                        {*<a href="#">[[Add/Remove Criteria:raw]]</a>*}
                    {*</div>*}
                    {*<div class="list-group-item">*}
                        {*<a href="#" class="ResetSearchCriteria">[[Reset Search Criteria]]</a>*}
                    {*</div>*}
            {*</form>*}
            {*</div>*}
        {*</div>*}
    {*</div>*}
{*</div>*}





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
