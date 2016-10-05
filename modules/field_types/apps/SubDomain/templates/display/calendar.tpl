<div id="irealtycalendar"></div>
{require component="jquery" file="jquery.js"}
{require component="jquery-ui" file="jquery-ui.js"}
{require component="jquery-ui" file="css/wfw/datepicker.css"}
{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
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
</script>
	<br><table align="left"><tr><td width="24" height="24" class="ui-state-active"></td><td>- [[Reserved]]</td><td width="24" height="24" style="border-width:1px; border-style:solid; background:#eee;"></td><td>- [[Free]]</td></tr></table>
	<br><br><br><br><a href="{page_path id='book_listing'}?listing_sid={$listing_sid}&field_sid={$field_sid}">[[Book Listing]]</a>
