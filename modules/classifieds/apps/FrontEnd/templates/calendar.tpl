{assign var=site_url value=$GLOBALS.site_url}
{assign var=listing_details_url value="{page_path id='listing'}$listing_sid"}
<div class="calendarWrapper">
	<div id="IrealtyCalendar"></div>
	<div class="calendarDescription">
		<div class="calendarDescriptionReserved ui-state-active"></div>
		<div class="calendarDescriptionText"> - [[Reserved]]</div>
		<div class="calendarDescriptionFree"></div>
		<div class="calendarDescriptionText"> - [[Free]]</div>
	</div>
</div>
{require component="jquery" file="jquery.js"}
{require component="jquery-ui" file="jquery-ui.js"}
{require component="jquery-ui" file="css/datepicker-custom/datepicker.css"}
{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
<script type="text/javascript">
    {literal}
    $(document).ready(function(){
        {/literal}
        var scheduledDays = [{foreach from=$calendar item=range name="calendar_range"}{foreach from=$range.RangeArray item=range_date name="rangedate"}[{$range_date}]{if not $smarty.foreach.rangedate.last},{/if}{/foreach}{if not $smarty.foreach.calendar_range.last},{/if}{/foreach}];{literal}

        $('div#IrealtyCalendar').datepicker({
            inline: true,
            numberOfMonths: 2,
	        firstDay: 7,
			showOtherMonths: true,
            beforeShowDay: function(date){
                for (i = 0; i < scheduledDays.length; i++) {
                    if (date.getMonth() == scheduledDays[i][1] - 1 && date.getDate() == scheduledDays[i][0] && date.getFullYear() == scheduledDays[i][2])
                    {
                        return [true,'ui-state-active'];
                    }
                }
                return [true,''];
            }
        });
    });
    {/literal}
</script>
