<div class="manageCalendarPage">
	<p><a href="{page_path id='listing_edit'}{$listing_sid}/">[[Back to edit listing]]</a></p>

	<br/>

	<h1>[[Manage Booking for $listing]]</h1>

	{display_error_messages}
	{display_success_messages}

	<div class="availabilityCalendarWrapper">
		<h3>[[Availability Calendar]]</h3>
		{include file="calendar.tpl" }
	</div>
	<div class="bookListingFormWrapper">
		<h3>[[Book Property]]</h3>

		<form action="" class="bookListingForm">
			<table class="form">
				{foreach from=$form_fields item=field}
					{if $field.id == 'sender_name' || $field.id == 'sender_email'}
						{input property=$field.id template="hidden.tpl"}
					{else}
						<tr>
							<td class="inputFormCaption inputFormCaption{$field.id}">
								[[$field.caption]]:
							</td>
							<td class="inputFormValue inputFormValue{$field.id}">
								{input property=$field.id}
							</td>
						</tr>
					{/if}
				{/foreach}
				<tr align="right">
					<td colspan=2>
						<input type="hidden" name="action" value="add"/>
						<input type="hidden" name="listing_sid" value="{$listing_sid}"/>
						<input type="hidden" name="field_sid" value="{$field_sid}"/>
						<input type="submit" value="[[Add:raw]]" class="button"/>
					</td>
				</tr>
			</table>
		</form>

		{require component="jquery" file="jquery.js"}
		{require component="jquery-ui" file="jquery-ui.js"}
		{require component="jquery-ui" file="css/wfw/datepicker.css"}
		{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
		<script type="text/javascript">
			$(document).ready(function () {
				$("input#input_from").datepicker({
					showOtherMonths: true,
					dateFormat: '{i18n->getRawDateFormat|replace:'%Y':'yy'|replace:'%m':'mm'|replace:'%d':'dd'}',
					firstDay: 1,
					onSelect: function () {
						var from = $(this).datepicker('getDate');
						var nextDayDate = new Date(from.getFullYear(), from.getMonth(), from.getDate() + 1);
						$("input#input_to").datepicker("option", "minDate", nextDayDate);
					}
				});
				$("input#input_to").datepicker({
					showOtherMonths: true,
					dateFormat: '{i18n->getRawDateFormat|replace:'%Y':'yy'|replace:'%m':'mm'|replace:'%d':'dd'}',
					firstDay: 1
				});
			});
		</script>
	</div>
	<div class="bookingRoster">

		<h3>[[Booking Roster]]</h3>
		<form action="">
			<table class="reservationGrid">
				<tr>
					<th>&nbsp;</th>
					<th class="cellValuePeriodFrom">[[Roster Start date]]</th>
					<th class="cellValuePeriodTo">[[Roster End date]]</th>
					<th class="cellValueComment">[[Comment]]</th>
					<th class="recordControls">[[Actions]]</th>
				</tr>
				{foreach from=$calendar item=day name=periods_block}
					<tr class="{cycle values = 'evenrow,oddrow' advance=false}" onmouseover="this.className='highlightrow'" onmouseout="this.className='{cycle values = 'evenrow,oddrow'}'">
						<td><input type="checkbox" name="periods[{$day.sid}]" value="1" id="checkbox_{$smarty.foreach.periods_block.iteration}"/></td>
						<td class="cellValuePeriodFrom">{tr type="date"}{$day.from}{/tr}</td>
						<td class="cellValuePeriodTo">{tr type="date"}{$day.to}{/tr}</td>
						<td class="cellValueComment">{$day.comment}</td>
						<td class="recordControls">
							<a href="?action=delete&amp;periods[{$day.sid}]=1&amp;listing_sid={$listing_sid}&amp;field_sid={$field_sid}" onclick="return confirm('Are you sure you want to delete this period?')" title="[[Delete:raw]]">[[Delete]]</a>
						</td>
					</tr>
					{foreachelse}
					<tr>
						<td colspan="5" align="center">[[This property is free to book]].</td>
					</tr>
				{/foreach}
			</table>
			<p class="multiActionControls">
				<a href="#" onclick="check_all();return false">[[Check all]]</a> / <a href="#" onclick="uncheck_all();return false">[[Uncheck all]]</a>
				[[Actions with Selected]]:
				<input type="submit" value="[[Delete:raw]]" class="delete-button" onclick="return confirm('[[Are you sure want to delete this periods?:raw]]')"/>
				<input type="hidden" name="action" value="delete"/>
				<input type="hidden" name="listing_sid" value="{$listing_sid}"/>
				<input type="hidden" name="field_sid" value="{$field_sid}"/>
			</p>
		</form>

		<script type="text/javascript">
			var total ={$smarty.foreach.periods_block.total};
			{literal}
			function check_all() {
				for (i = 1; i <= total; i++) {
					if (checkbox = document.getElementById('checkbox_' + i))
						checkbox.checked = true;
				}
			}

			function uncheck_all() {
				for (i = 1; i <= total; i++) {
					if (checkbox = document.getElementById('checkbox_' + i))
						checkbox.checked = false;
				}
			}
			{/literal}
		</script>

	</div>
</div>
