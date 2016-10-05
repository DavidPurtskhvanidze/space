{capture name="input_text_field_from" assign="input_text_field_from"} <input type="text" name="{$id}[not_less]" class="searchIntegerLess form-control" value="{$value.not_less|escape}" id="{$id}" /> {/capture}
{capture name="input_text_field_to" assign="input_text_field_to"}   <input type="text" name="{$id}[not_more]" class="searchIntegerMore form-control" value="{$value.not_more|escape}" /> {/capture}

    <span class="decimalInputs">
{$input_text_field_from} <span class="to">[[to]]</span> {$input_text_field_to}
</span>
