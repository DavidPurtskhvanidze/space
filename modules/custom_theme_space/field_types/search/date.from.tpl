<div class="input-group">
    <input type="text" name="{$id}[not_earlier]" value="{$value.not_earlier|escape}" class="form-control datepicker" />
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-calendar"></span>
    </span>
</div>
{capture name="date_format_example" assign="date_format_example"}{tr type="date"}now{/tr}{/capture}
{i18n->getDateFormat assign="date_format"}
<p>[[date format: '$date_format', for example: '$date_format_example']]</p>
{include file="miscellaneous^datepicker.tpl"}
