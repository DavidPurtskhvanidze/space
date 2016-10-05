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
<div class="listingFormContainer">
    <div class="container">
        <div class="stepsWrap text-center">
            <ul class="nav nav-tabs nav-justified" role="tablist">
                {foreach $steps as $step}
                    {if $step.current}
                        {$class='active'}
                    {elseif $step@iteration > $maxReachedStepsCount}
                        {$class='disabled'}
                    {elseif $step@iteration <= $maxReachedStepsCount}
                        {$class='enable'}
                    {else}
                        {$class=''}
                    {/if}

                    <li class="{$class}">

                        <div class="visible-lg-block text-center stepLine {if $step@first}first{/if}{if $step@last} last{/if}">
                            <span class="badge {$class}">{$step@iteration}</span>
                            {if $step@first}<div class="firstStep"></div>{/if}
                            {if $step@last}<div class="lastStep"></div>{/if}
                            {if !$step@last && $step@iteration >= $maxReachedStepsCount}<div class="enableStep"></div>{/if}
                        </div>
                        {if $step@iteration <= $maxReachedStepsCount && !$step.current}
                            <a href="?action_go_to_step=1&amp;step={$step@iteration}&amp;add_listing_session_id={$add_listing_session_id}">[[$step.title]]</a>
                        {else}
                            <a href="#">[[$step.title]]</a>
                        {/if}
                    </li>
                {/foreach}
            </ul>
        </div>
        {strip}
        <div class="space-20"></div>
            <hr class="visible-xs"/>
        <div class="space-20"></div>
        <div class="row">
            <div class="col-sm-6 form-control-static vcenter">
                <strong>
                    [[FormFieldCaptions!Category]]:&nbsp;&nbsp;
                </strong>
                {foreach from=$ancestors item=ancestor name="ancestors_cycle"}
                    [[$ancestor.caption]]&nbsp;&nbsp;<i class="fa fa-caret-right"></i>&nbsp;&nbsp;
                {/foreach}
            </div>
            <div class="col-sm-6 text-right vcenter">
                <a class="btn btn-orange h5" href="{page_path id='listing_add'}?listing_package_sid={$listing_package_sid}">[[Change Category]]</a>
            </div>
        </div>
        {/strip}
        <div class="space-20"></div>
        <div class="bg-info">
            <a href="{page_path module='classifieds' function='fill_listing_form'}?action=search&category_id={$categoryId}&add_listing_session_id={$add_listing_session_id}"
               onclick="return openDialogWindow('[[Choose a Listing as a Template]]', this.href, 900, true)">[[Сreate a new ad based on an existing listing]]</a>
        </div>

        {display_error_messages}
        <div class="space-20"></div>
        <div class="bg-info">
                [[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]
        </div>
    </div>
    <div class="space-20"></div>
    <div class="bg-grey">
        <div class="space-20"></div>
        <div class="space-20"></div>
        <div class="container">
            <form method="post" action="" enctype="multipart/form-data" id="{$id}" name="addListing" class="form-horizontal">
            <input type="hidden" name="step" value="{$currentStep}"/>
            {CSRF_token}
            <input type="hidden" name="add_listing_session_id" value="{$add_listing_session_id}"/>
            {$formContent}
            {if $stepIsLast}
                {module name="listing_repost" function="display_add_listing_settings"}
                <fieldset>
                    <legend>[[Listing Features]]</legend>
                    {if $freeFeatures|count > 0}
                        <div class="bg-info">[[Enable free listing features]]:</div>
                        <div class="space-20"></div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-2">
                                {foreach from=$freeFeatures item=feature}
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="custom-form-control">
                                            <input id="{$feature.id}" type="checkbox" name="selectedOptionIds[]" value="{$feature.id}" {if in_array($feature.id, $selectedOptionIds)}checked="checked"{/if} />
                                            <label class="checkbox" for="{$feature.id}">[[$feature.caption]]</label>
                                        </div>
                                    </div>
                                {/foreach}
                            </div>
                        </div>
                        <div class="space-20"></div>
                    {/if}
                    {if $paidFeatures|count > 0}
                        <div class="bg-info">[[Enable paid listing features:]]</div>
                        <div class="space-20"></div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-2">
                                {foreach from=$paidFeatures item=feature}
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="custom-form-control">
                                            <input id="{$feature.id}" type="checkbox" name="selectedOptionIds[]" value="{$feature.id}" {if in_array($feature.id, $selectedOptionIds)}checked="checked"{/if} />
                                            <label class="checkbox" for="{$feature.id}">[[$feature.caption]] {display_price_with_currency amount=$feature.price}</label>
                                        </div>
                                    </div>
                                {/foreach}
                            </div>
                        </div>
                        <div class="space-20"></div>
                    {/if}
                </fieldset>
            {/if}

            <div class="formConrols">
                <div class="row">
                    {if !$stepIsFirst}
                        <div class="col-xs-5 text-center">
                            <button type="submit" class="btn btn-link go-back h5" name="action_back" value="1"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;[[Go Back:raw]]</button>
                        </div>
                        <div class="col-xs-7">
                            {if !$stepIsLast}
                                <button type="submit" class="btn btn-orange h5" name="action_forward" value="1">[[Save & Continue:raw]]</button>
                            {else}
                                <button type="submit" class="btn btn-orange h5" name="action_add" value="1">[[Save Listing:raw]]</button>
                            {/if}
                        </div>
                    {else}
                        <div class="col-sm-12 text-center">
                            {if !$stepIsLast}
                                <button type="submit" class="btn btn-orange h5" name="action_forward" value="1">[[Save & Continue:raw]]</button>
                            {else}
                                <button type="submit" class="btn btn-orange h5" name="action_add" value="1">[[Save Listing:raw]]</button>
                            {/if}
                        </div>
                    {/if}
                </div>
            </div>
        </form>
        </div>
        <div class="space-20"></div>
        <div class="space-20"></div>
        <div class="space-20"></div>
    </div>
    <script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>
</div>

