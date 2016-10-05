<div class="manageCalendarPage">
	<p><a href="{page_path id='listing'}{$listing_sid}">[[Back to view listing]]</a></p>
	<br/>

	<h1>[[Book $listingType $listing]]</h1>

	{display_error_messages}
	{display_success_messages}

	{if !$hideForm}
		<div class="availabilityCalendarWrapper">
			<h3>[[Availability Calendar]]</h3>
			{include file="calendar.tpl" }
		</div>
		<div class="bookListingFormWrapper">
			<h3>[[Send a booking request]]</h3>
			{require component="jquery" file="jquery.js"}
			{require component="jquery-ui" file="jquery-ui.js"}
			{require component="jquery-ui" file="css/wfw/datepicker.css"}
			{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
			<form action="" class="bookListingForm">
				<table class="form">
					{foreach from=$form_fields item=field}
						<tr>
							<td class="inputFormCaption inputFormCaption{$field.id}">
								[[$field.caption]]:
							</td>
							<td class="inputFormValue inputFormValue{$field.id}">
								{input property=$field.id}
							</td>
						</tr>
					{/foreach}
					<tr align="right">
						<td></td>
						<td>
							<input type="hidden" name="action" value="book"/>
							<input type="hidden" name="listing_sid" value="{$listing_sid}"/>
							<input type="hidden" name="field_sid" value="{$field_sid}"/>
							<input type="submit" value="[[Send:raw]]" class="button"/>
						</td>
					</tr>
				</table>
			</form>
			<script type="text/javascript">
				$(document).ready(function () {
					$("input#input_from").datepicker(
							{
								dateFormat: '{i18n->getRawDateFormat|replace:'%Y':'yy'|replace:'%m':'mm'|replace:'%d':'dd'}',
								numberOfMonths: 1,
								firstDay: 7,
								showOtherMonths: true,
								onSelect: function () {
									var from = $(this).datepicker('getDate');
									var nextDayDate = new Date(from.getFullYear(), from.getMonth(), from.getDate() + 1);
									$("input#input_to").datepicker("option", "minDate", nextDayDate);
								}
							});
					$("input#input_to").datepicker(
							{
								dateFormat: '{i18n->getRawDateFormat|replace:'%Y':'yy'|replace:'%m':'mm'|replace:'%d':'dd'}',
								numberOfMonths: 1,
								firstDay: 7,
								showOtherMonths: true
							});
				});
			</script>
		</div>
	{/if}
</div>
