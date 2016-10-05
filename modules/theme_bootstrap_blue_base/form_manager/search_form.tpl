<div class="container">
    {if $form_info.title}<h1 class="page-title">[[$form_info.title]]</h1>{/if}
    {extension_point name='modules\main\apps\FrontEnd\IAdvancedSearchFormAdditionRenderer' categorySid = $category_sid}
    <div class="space-20"></div>
</div>

<div class="bg-grey">
    <div class="container">
        <form class="form-horizontal advancedSearchForm" role="form" action="{page_path id='search_results'}">
            <input type="hidden" name="action" value="search"/>
            <input type="hidden" name="category_sid[tree][]" value="{$category_sid}"/>
            <fieldset>
                {foreach from=$fields_to_display item=field_id}
                {if isset($form_fields[$field_id.value])}
                <div class="row">
                    <div class="col-sm-11 {if $form_fields[$field_id.value]['type'] eq 'multilist'}col-sm-offset-1{/if}">
                        <div class="form-group form-field-{$form_fields[$field_id.value]['type']}">
                            {if $form_fields[$field_id.value]['type'] neq 'multilist'}
                                <label class="
                            {if $form_fields[$field_id.value]['type']|in_array:['boolean', 'pictures']}col-xs-5{/if} col-sm-2 control-label">
                                    [[FormFieldCaptions!{$form_fields[$field_id.value]['caption']}]] <span class="fieldCaption {$form_fields[$field_id.value]['type']}"></span>
                                </label>
                            {/if}
                            <div class="{if $form_fields[$field_id.value]['type']|in_array:['boolean', 'pictures'] eq 'boolean'}col-xs-7{elseif $form_fields[$field_id.value]['type'] neq 'multilist'}col-sm-10{/if}">{search property=$field_id.value}</div>
                        </div>
                    </div>
                </div>
                {else}
                {if $field_id.value eq 'Fieldset'}
            </fieldset>
            <fieldset class="fieldset {$field_id.caption|lcfirst|replace:' ':''}">
                <legend>[[{$field_id.caption}]]</legend>
                {/if}
                {/if}
                {/foreach}
            </fieldset>
            <div class="form-group">
                <div class="text-center">
                    <button type="submit" class="btn btn-orange h5">
                        [[Search]]
                    </button>
                </div>
            </div>
        </form>
        <div class="space-20"></div>
    </div>
</div>
{*{if $form_fields[$field_id.value]['type'] eq 'boolean'}*}
