{capture name="input_text_field_from" assign="input_text_field_from"} <input type="text" name="{$id}[not_less]" class="searchIntegerLess form-control" value="{$value.not_less|escape}" /> {/capture}
{capture name="input_text_field_to" assign="input_text_field_to"}   <input type="text" name="{$id}[not_more]" class="searchIntegerMore form-control" value="{$value.not_more|escape}" /> {/capture}

[[$input_text_field_from to $input_text_field_to]]
