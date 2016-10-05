{capture name="input_text_field_from" assign="input_text_field_from"} <input type="text" name="{$id}[not_less]" class="searchIntegerLess form-control money" value="{$value.not_less|escape}" id="{$id}" placeholder="[[from]]" /> {/capture}
{capture name="input_text_field_to" assign="input_text_field_to"}   <input type="text" name="{$id}[not_more]" class="searchIntegerMore form-control money" value="{$value.not_more|escape}" placeholder="[[to]]" /> {/capture}
<div class="row">
    <span class = "moneyInputs">
        <div class="col-sm-6">
            {$input_text_field_from}
        </div>
        <div class="col-sm-6">
            {$input_text_field_to}
        </div>
    </span>
</div>
{extension_point name='modules\main\apps\FrontEnd\ISearchFormElement' manipulatedTypeId=$id signNum=$signs_num}
