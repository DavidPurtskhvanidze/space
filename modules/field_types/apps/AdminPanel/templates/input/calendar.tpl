{require component="jquery" file="jquery.js"}
{require component="jquery-ui" file="jquery-ui.js"}
{require component="jquery-ui" file="css/wfw/datepicker.css"}
{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
{if $listing_sid}
<div id="irealtycalendar"></div>
<script>
{literal}
$(document).ready(function(){
    {/literal}
   var scheduledDays = [{foreach from=$calendar item=range name="calendar_range"}{foreach from=$range.RangeArray item=range_date name="rangedate"}[{$range_date}]{if not $smarty.foreach.rangedate.last},{/if}{/foreach}{if not $smarty.foreach.calendar_range.last},{/if}{/foreach}];{literal}
    $('div#irealtycalendar').datepicker({
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
</script><br />
	
	<a href="{page_path module='classifieds' function='edit_calendar'}?listing_sid={$listing_sid}&field_sid={$field_sid}">[[Edit Calendar]]</a>
{else}
	<p>[[You will be able to edit the calendar after you create a listing.]]</p>
{/if}
