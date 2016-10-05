{capture name="input_text_field_from" assign="input_text_field_from"} <input type="text" class="searchDateNotEarlier" name="{$id}[not_earlier]" id="{$id}" value="{$value.not_earlier|escape}" /> {/capture}
{capture name="input_text_field_to" assign="input_text_field_to"}   <input type="text" class="searchDateNotLater" name="{$id}[not_later]" value="{$value.not_later|escape}" /> {/capture}
{capture name="date_format_example" assign="date_format_example"}{tr type="date"}now{/tr}{/capture}

{i18n->getDateFormat assign="date_format"}

[[$input_text_field_from to $input_text_field_to]]<br />
<small>[[date format: '$date_format', for example: '$date_format_example']]</small>
