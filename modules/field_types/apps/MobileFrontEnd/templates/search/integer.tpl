{capture name="input_text_field_from" assign="input_text_field_from"} <input type="text" name="{$id}[not_less]" id="{$id}" class="searchIntegerLess" value="{$value.not_less|escape}" /> {/capture}
{capture name="input_text_field_to" assign="input_text_field_to"}   <input type="text" name="{$id}[not_more]" class="searchIntegerMore" value="{$value.not_more|escape}" /> {/capture}

[[$input_text_field_from]]
<span> [[to]]</span>
[[$input_text_field_to]]
