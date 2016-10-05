<div class="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <a href="{$GLOBALS.site_url}/manage_listings/">[[Manage Listings]]</a>
        </li>
        <li>
            <a href="{page_path id='edit_listing'}?listing_id={$listing_sid}">[[Edit Listing]]</a>
        </li>
        <li>
            [[Edit Calendar]]
        </li>
    </ul>
</div>

<div class="page-content">
    <div class="page-header">
        <h1 class="lighter">[[Edit Calendar]]</h1>
    </div>

    <h2>[[Calendar]]</h2>

    <div id="irealtycalendar"></div>
    {require component="jquery" file="jquery.js"}
    {require component="jquery-ui" file="jquery-ui.js"}
    {require component="jquery-ui" file="css/wfw/datepicker.css"}
    {require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
    <script>
        {literal}
        $(document).ready(function () {
            {/literal}
            var scheduledDays = [{foreach from=$calendar item=range name="calendar_range"}{foreach from=$range.RangeArray item=range_date name="rangedate"}[{$range_date}]{if not $smarty.foreach.rangedate.last}, {/if}{/foreach}{if not $smarty.foreach.calendar_range.last}, {/if}{/foreach}];
            {literal}
            $('div#irealtycalendar').datepicker({
                inline: true,
                numberOfMonths: 2,
                firstDay: 1,
                showOtherMonths: true,
                beforeShowDay: function (date) {
                    for (i = 0; i < scheduledDays.length; i++) {
                        if (date.getMonth() == scheduledDays[i][1] - 1 && date.getDate() == scheduledDays[i][0] && date.getFullYear() == scheduledDays[i][2]) {
                            return [true, 'ui-state-active'];
                        }
                    }
                    return [true, ''];
                }
            });
        });
        {/literal}
    </script>

    <h2 class="lighter">[[Add New Period]]</h2>
    <hr>
    {display_error_messages}
    {display_success_messages}

    <div class="info">[[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]</div>

    <form class="form-horizontal" role="form">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="listing_sid" value="{$listing_sid}">
        <input type="hidden" name="field_sid" value="{$field_sid}">

        {foreach from=$form_fields item=field}
            <div class="form-group">
                <label class="col-sm-3 control-label inputFormCaption inputFormCaption{$field.id}">
                    [[$field.caption]]: {if $field.is_required}<i class="icon-asterisk smaller-60  smaller-60"></i>{/if}
                </label>

                <div class="col-sm-8 inputFormValue inputFormValue{$field.id}">
                    {input property=$field.id}
                </div>
            </div>
        {/foreach}
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>

            <div class="col-sm-8"><input type="submit" value="[[Add:raw]]" class="btn btn-default"></div>
        </div>
    </form>

    <script type="text/javascript">
        $(document).ready(function () {
            $("input#from, input#to").attr('data-date-format', '{i18n->getRawDateFormat|replace:'%Y':'yyyy'|replace:'%m':'mm'|replace:'%d':'dd'}');
            $("input#from").datepicker({
                showOtherMonths: true,
                onSelect: function () {
                    var from = $(this).datepicker('getDate');
                    var nextDayDate = new Date(from.getFullYear(), from.getMonth(), from.getDate() + 1);
                    $("input#to").datepicker("option", "minDate", nextDayDate);
                }
            });
            $("input#to").datepicker({
                showOtherMonths: true
            });
        });
    </script>

    <h2 class="lighter">[[Reserved Periods]]</h2>
    <hr>
    <form name="itemSelectorForm" class="form-horizontal" role="form">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="listing_sid" value="{$listing_sid}">
        <input type="hidden" name="field_sid" value="{$field_sid}">

        <table class="table table-hover table-striped">
            <thead>
            <th class="center align-middle">
                <label>
                    <input type="checkbox" class="checkAll"/>
                    <span class="lbl"></span>
                </label>
            </th>
            <th>[[From]]</th>
            <th>[[To]]</th>
            <th>[[Comment]]</th>
            <th>[[Actions]]</th>
            </thead>
            <tbody>
            {foreach from=$calendar item="day" name="periods_block"}
                <tr class="{cycle values="odd,even"}">
                    <td class="center align-middle">
                        <label>
                            <input type="checkbox" name="periods[{$day.sid}]" value="1"
                                   id="checkbox_{$smarty.foreach.periods_block.iteration}">
                            <span class="lbl"></span>
                        </label>
                    </td>
                    <td>{tr type="date"}{$day.from}{/tr}</td>
                    <td>{tr type="date"}{$day.to}{/tr}</td>
                    <td>{$day.comment}</td>
                    <td>
                        <a class="itemControls delete"
                           href="?action=delete&periods[{$day.sid}]=1&listing_sid={$listing_sid}&field_sid={$field_sid}"
                           onclick="return confirm('[[Are you sure you want to delete this period?:raw]]')"
                           title="[[Delete:raw]]">[[Delete]]</a>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
        <input class="btn btn-default" type="submit" value="[[Delete selected:raw]]"
               onclick="return confirm('[[Are you sure that you want to delete selected periods?:raw]]')">
    </form>
    <script type="text/javascript">
        {literal}
        $(document).ready(function () {
            $("form[name=itemSelectorForm] .checkAll").change(function () {
                $("form[name=itemSelectorForm] input[name^=periods]").prop("checked", $(this).prop("checked"));
            });
        });
        {/literal}
    </script>
</div>
