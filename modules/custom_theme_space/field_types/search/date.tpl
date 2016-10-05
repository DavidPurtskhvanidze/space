{capture name="input_text_field_from" assign="input_text_field_from"} <input type="text" name="{$id}[not_earlier]" value="{$value.not_earlier|escape}" class="form-control datepicker" id="dateFrom" placeholder="[[from:raw]]"> {/capture}
{capture name="input_text_field_to" assign="input_text_field_to"} <input type="text" name="{$id}[not_later]" value="{$value.not_later|escape}" class="form-control datepicker" id="dateTo" placeholder="[[to:raw]]"> {/capture}

<div class="row">
    <div class="col-sm-6">
        <div class="input-group datepicker-top-field">
            [[$input_text_field_from]]
             <span class="input-group-addon">
                <img src="{url file="main^img/calendar.png"}" alt="">
            </span>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="input-group">
            [[$input_text_field_to]]
            <span class="input-group-addon">
                <img src="{url file="main^img/calendar.png"}" alt="">
            </span>
        </div>
    </div>
</div>
{include file="miscellaneous^datepicker.tpl"}


