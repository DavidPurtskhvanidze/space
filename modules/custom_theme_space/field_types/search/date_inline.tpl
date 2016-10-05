{capture name="input_text_field_from" assign="input_text_field_from"} <input type="text" name="{$id}[not_earlier]" value="{$value.not_earlier|escape}" class="form-control datepicker" id="dateFrom" placeholder="{$placeholderFrom}"> {/capture}
{capture name="input_text_field_to" assign="input_text_field_to"} <input type="text" name="{$id}[not_later]" value="{$value.not_later|escape}" class="form-control datepicker" id="dateTo" placeholder="{$placeholderTo}"> {/capture}
{capture name="date_format_example" assign="date_format_example"}{tr type="date"}now{/tr}{/capture}

{i18n->getDateFormat assign="date_format"}
<div class="form-group formWithHelp">
    <div class="form-group">
	    {if !$hideLabels}
		    <label for="dateFrom">[[Period]]: [[from]]</label>
	    {/if}
        <div class="input-group">
            [[$input_text_field_from]]
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group">
	    {if !$hideLabels}
		    <label for="dateTo">[[to]]</label>
	    {/if}
        <div class="input-group">
            [[$input_text_field_to]]
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
    <span  class="help-block">[[date format: '$date_format', for example: '$date_format_example']]</span>
</div>
{include file="miscellaneous^datepicker.tpl"}
