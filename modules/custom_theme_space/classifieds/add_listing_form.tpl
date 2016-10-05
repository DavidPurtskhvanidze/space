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
<div class="add-listing-box">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
			<div class="steps-wrap">
				<ul class="list-unstyled text-center">
					<li class="first-step">
						<span class="add-listing-step-title">
							<a href="{page_path id='listing_add'}?listing_package_sid={$listing_package_sid}">
								[[Change Category]]
							</a>
						</span>
						<span class="add-listing-step active"></span>
						<span class="add-listing-step-line active"></span>
					</li>
					{foreach $steps as $step}

						{if $step.current}
							{$class='active'}
						{elseif $step@iteration > $maxReachedStepsCount}
							{$class='disabled'}
						{elseif $step@iteration <= $maxReachedStepsCount}
							{$class='enable'}
						{/if}
						<li class="{$class}">
							<span class="add-listing-step-title">
								{if $step@iteration <= $maxReachedStepsCount && !$step.current}
									<a href="?action_go_to_step=1&amp;step={$step@iteration}&amp;add_listing_session_id={$add_listing_session_id}">
										[[$step.title]]
									</a>
								{else}
									<a href="#">[[$step.title]]</a>
								{/if}
							</span>
							<span class="add-listing-step {$class}"></span>
							<span class="add-listing-step-line {$class}"></span>

							{*{if !$step@last && $step@iteration >= $maxReachedStepsCount}<span class="enable-step">1</span>{/if}*}
						</li>
					{/foreach}
				</ul>
			</div>
			<div class="clearfix"></div>

			{display_error_messages}

			<div class="alert alert-warning text-center">
				<a href="{page_path module='classifieds' function='fill_listing_form'}?action=search&category_id={$categoryId}&add_listing_session_id={$add_listing_session_id}"
				   onclick="return openDialogWindow('[[Choose a Listing as a Template]]', this.href, 900, true)">[[Сreate a new ad based on an existing listing]]</a>
			</div>
			<div class="alert alert-warning text-center">
				<span class="i-img-box">
					<img src="{url file="main^img/!.png"}" alt="">
				</span>
				<span class="alert-text-box">
					[[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]
				</span>
			</div>

			<form method="post" action="" enctype="multipart/form-data" id="{$id}" name="addListing" class="form-horizontal">
				{CSRF_token}
				<input type="hidden" name="step" value="{$currentStep}"/>
				<input type="hidden" name="add_listing_session_id" value="{$add_listing_session_id}"/>
				{$formContent}
				{if $stepIsLast}
					{module name="listing_repost" function="display_add_listing_settings"}
					<fieldset>
						<legend>[[Listing Features]]</legend>
						{if $freeFeatures|count > 0}
							<div class="form-group">
								<div class="col-sm-12">
									<div class="alert alert-warning text-center">[[Enable free listing features]]:</div>
									<div class="row">
										{foreach from=$freeFeatures item=feature}
											<div class="col-xs-6 col-sm-4">
												<div class="checkbox">
													<label>
														<input type="checkbox" name="selectedOptionIds[]" value="{$feature.id}" {if in_array($feature.id, $selectedOptionIds)}checked="checked"{/if} />
														[[$feature.caption]]
													</label>
												</div>
											</div>
										{/foreach}
									</div>
								</div>
							</div>
						{/if}
						{if $paidFeatures|count > 0}
							<div class="form-group">
								<div class="col-sm-12">
									<div class="alert alert-warning text-center">[[Enable paid listing features:]]</div>
									<div class="row">
										{foreach from=$paidFeatures item=feature}
											<div class="col-xs-6 col-sm-4">
												<div class="checkbox">
													<label>
														<input type="checkbox" name="selectedOptionIds[]" value="{$feature.id}" {if in_array($feature.id, $selectedOptionIds)}checked="checked"{/if} />
														[[$feature.caption]] {display_price_with_currency amount=$feature.price}
													</label>
												</div>
											</div>
										{/foreach}
									</div>
								</div>
							</div>
						{/if}
					</fieldset>
				{/if}

				<div class="formConrols text-center">
					{if !$stepIsFirst}
						<button type="submit" class="default-button wb" name="action_back" value="1">[[Go Back:raw]]</button>
					{/if}

					{if !$stepIsLast}
						<button type="submit" class="default-button wb" name="action_forward" value="1">[[Save & Continue:raw]]</button>
					{else}
						<button type="submit" class="default-button wb" name="action_add" value="1">[[Save Listing:raw]]</button>
					{/if}
				</div>
			</form>
            <script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>
        </div>
    </div>
</div>

