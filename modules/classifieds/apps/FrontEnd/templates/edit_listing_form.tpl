<script type="text/javascript">
	$(document).ready(function () {

		$(".tabs .step").hide();

		$("ul.steps a").click(function () {
			var linkHref = $(this).attr("href");

			$(".tabs .step").hide();
			$(".tabs " + linkHref).show();

			$("ul.steps a").parent("li").removeClass("active");
			$(this).parent("li").addClass("active");

			return false;
		});

		$("ul.steps a:first").click();
	});
</script>

{display_success_messages}
{if !empty($not_valid_property_ids)}
	<div class="popup">
		<form method="post" action="" enctype="multipart/form-data">
			{display_error_messages}
			<table class="requiredFieldForm">
				{foreach from=$form_fields item=form_field}
					{if in_array($form_field.id, $not_valid_property_ids)}
						<tr>
							<td class="fieldCaption">[[$form_field.caption]] {if $form_field.is_required}<span class="asterisk">*</span>{/if}</td>
							<td>{input property=$form_field.id}</td>
						</tr>
					{/if}
				{/foreach}
				<tr>
					<td>&nbsp;</td>
					<td class="formControls">
						<input type="hidden" name="action" value="save_info"/>
						<input type="hidden" name="listing_id" value="{$listing.id}"/>
						<input type="submit" value="[[Save:raw]]" class="button"/>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<script type="text/javascript">
		$(document).ready(function () {
			$(".popup").dialog({
				width: 500,
				height: "auto",
				maxHeight: 600,
				position: ['center', 'center'],
				modal: true,
				title: "[[Please fill in the required fields!:raw]]"
			});
		});
	</script>
{/if}

<ul class="steps">
	{strip}
	{foreach from=$steps item="step" key="stepNumber"}
		<li>
			<a href="#step{$stepNumber}">[[$step.title]]</a>
		</li>
	{/foreach}
	{/strip}
</ul>

<div class="mandatoryFieldsMessage hint">
	[[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]
</div>

<form method="post" action="" enctype="multipart/form-data">
    {CSRF_token}
	<div class="tabs">
		{$formContent}
	</div>
	<div class="formConrols">
		<input type="hidden" name="action" value="save_info"/>
		<input type="hidden" name="listing_id" value="{$listing.id}"/>
		<input type="submit" value="[[Save:raw]]" class="button"/>
	</div>
</form>
