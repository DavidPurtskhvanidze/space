{capture name="date_format_example" assign="date_format_example"}{tr type="date"}now{/tr}{/capture}

{i18n->getDateFormat assign="date_format"}
{capture name="input_text_field_from" assign="input_text_field_from"}
    <input type="text" name="{$id}[not_earlier]" value="{$value.not_earlier|escape}" class="form-control datepicker" id="dateFrom" placeholder="{$date_format}">
{/capture}
{capture name="input_text_field_to" assign="input_text_field_to"}
    <input type="text" name="{$id}[not_later]" value="{$value.not_later|escape}" class="form-control datepicker" id="dateTo" placeholder="{$date_format}">
{/capture}

<div class="form-group full-width-item">
    <label for="activation_date" class="control-label">[[FormFieldCaptions!Period Date From]]</label>
    [[$input_text_field_from]]
</div>
<div class="form-group full-width-item">
    <label for="activation_date" class="control-label">[[FormFieldCaptions!Period Date To]]</label>
    [[$input_text_field_to]]
</div>
{*<span  class="help-block">[[date format: '$date_format', for example: '$date_format_example']]</span>*}

{include file="miscellaneous^datepicker.tpl"}

