{capture name="input_text_field_from" assign="input_text_field_from"} <input type="text" name="{$id}[not_less]" class="searchIntegerLess form-control money" value="{$value.not_less|escape}" id="{$id}" /> {/capture}
{capture name="input_text_field_to" assign="input_text_field_to"}   <input type="text" name="{$id}[not_more]" class="searchIntegerMore form-control money" value="{$value.not_more|escape}" /> {/capture}

<span class = "moneyInputs">
{$input_text_field_from} <span class="to">[[to]]</span> {$input_text_field_to}
</span>
{extension_point name='modules\main\apps\FrontEnd\ISearchFormElement' manipulatedTypeId=$id signNum=$signs_num}
