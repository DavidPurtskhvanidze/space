<script type="text/javascript ">
	$(document).ready(function () {
		$("form[name='addListing'] :input").change(function() {
			$("form[name='addListing']").data('changed', true);
		});
		$("ul.steps a, input[name='action_back']").click(function () {
			var flagOfChanges = false;{* The future’s in the air, I can feel it everywhere blowing with the flag of change… *}
			if(typeof CKEDITOR !== 'undefined')
			{
				for (var i in CKEDITOR.instances)
				{
					if ((flagOfChanges = CKEDITOR.instances[i].checkDirty()) === true)
					{
						break;
					}
				}
			}
			if ($("form[name='addListing']").data('changed') || flagOfChanges)
			{
				return confirm('[[Are you sure you want to go back?\nIf you do, you will lose all unsaved information added on this step.\nPress "Save & Continue", and then try again.:raw]]');
			}
		})
	});
</script>

<ul class="steps">
	{strip}
	{foreach from=$steps item="step"}
		<li{if $step.current} class="active"{/if}>
			{if $step@iteration <= $maxReachedStepsCount && !$step.current}
				<a href="?action_go_to_step=1&amp;step={$step@iteration}&amp;add_listing_session_id={$add_listing_session_id}">[[$step.title]]</a>
			{else}
				<span>[[$step.title]]</span>
			{/if}
		</li>
	{/foreach}
	{/strip}
</ul>

{display_error_messages}

<div class="mandatoryFieldsMessage hint">
	<a href="{page_path module='classifieds' function='fill_listing_form'}?action=search&category_id={$categoryId}&add_listing_session_id={$add_listing_session_id}"
	   onclick="return openDialogWindow('[[Choose a Listing as a Template]]', this.href, 900, true)">[[Сreate a new ad based on an existing listing]]
	</a>
</div>
<div class="mandatoryFieldsMessage hint">[[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]</div>

<form method="post" action="" enctype="multipart/form-data" id="{$id}" name="addListing">
    {CSRF_token}
	<div>
		<input type="hidden" name="step" value="{$currentStep}"/>
		<input type="hidden" name="add_listing_session_id" value="{$add_listing_session_id}"/>
		{$formContent}
		{if $stepIsLast}
			<table class="featuresAndRepostTable">
				<tr>
					<td colspan="2">{module name="listing_repost" function="display_add_listing_settings"}</td>
				</tr>
				<tr>
					<td colspan="2"><h3>[[Listing Features]]</h3></td>
				</tr>
				<tr>
					<td colspan="2">
						{if $freeFeatures|count > 0}
							<div class="freeFeaturesActivation">
								<p>[[Enable free listing features]]:</p>
								{foreach from=$freeFeatures item=feature}
									<div>
										<input id="{$feature.id}" type="checkbox" name="selectedOptionIds[]" value="{$feature.id}" {if in_array($feature.id, $selectedOptionIds)}checked="checked"{/if} />
										<label for="{$feature.id}">[[$feature.caption]]</label>
									</div>
								{/foreach}
							</div>
						{/if}

						{if $paidFeatures|count > 0}
							<div class="paidFeaturesActivation">
								<p>[[Enable paid listing features:]]</p>
								{foreach from=$paidFeatures item=feature}
									<div>
										<input id="{$feature.id}" type="checkbox" name="selectedOptionIds[]" value="{$feature.id}" {if in_array($feature.id, $selectedOptionIds)}checked="checked"{/if} />
										<label for="{$feature.id}">[[$feature.caption]] {display_price_with_currency amount=$feature.price}</label>
									</div>
								{/foreach}
							</div>
						{/if}
					</td>
				</tr>
			</table>
		{/if}

		<div class="formConrols">
			{if !$stepIsFirst}
				<input type="submit" name="action_back" value="[[Go Back:raw]]"/>
			{/if}

			{if !$stepIsLast}
				<input type="submit" name="action_forward" value="[[Save & Continue:raw]]"/>
			{else}
				<input type="submit" name="action_add" value="[[Save Listing:raw]]" class="button" />
			{/if}
		</div>
	</div>
</form>
