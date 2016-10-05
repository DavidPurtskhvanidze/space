{capture name="input_text_field_from" assign="input_text_field_from"} <input type="text" name="{$id}[not_earlier]" value="{$value.not_earlier|escape}" class="form-control datepicker" id="dateFrom" placeholder="[[from:raw]]"> {/capture}
{capture name="input_text_field_to" assign="input_text_field_to"} <input type="text" name="{$id}[not_later]" value="{$value.not_later|escape}" class="form-control datepicker" id="dateTo" placeholder="[[to:raw]]"> {/capture}
{capture name="date_format_example" assign="date_format_example"}{tr type="date"}now{/tr}{/capture}

{i18n->getDateFormat assign="date_format"}
<div class="row">
    <div class="col-sm-6">
        <div class="input-group">
            [[$input_text_field_from]]
             <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="input-group">
            [[$input_text_field_to]]
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
	    <span class="help-block">[[date format: '$date_format', for example: '$date_format_example']]</span>
    </div>
</div>
{include file="miscellaneous^datepicker.tpl"}


