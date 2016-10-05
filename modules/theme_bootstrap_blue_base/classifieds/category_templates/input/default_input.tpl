{$listingAddressFields = ['Address','City','State','ZipCode']}

{step title="Listing Information"}
{if $form_fields.Sold and $display_sold_field}
    <fieldset>
        <legend>[[Already Sold?]]</legend>
        <div class="row">
            <div class="col-sm-11">
                <div class="form-group row">
                    <div class="col-sm-3 col-sm-offset-4">
                        {input property=Sold template='boolean_right.tpl'}
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
{/if}
	<fieldset>
		<legend>[[Listing Information]]</legend>
        <div class="row">
            <div class="col-sm-11">
                {$excludeAlso = ['Video','ListingRating','feature_youtube_video_id','Description','Sold','Price']}
                {$listingFieldsToExclude = array_merge($listingAddressFields, $excludeAlso, $calendarTypeFieldIds)}

                {foreach from=$form_fields item=fieldInfo}
                    {if !in_array($fieldInfo.id, $listingFieldsToExclude)}
                        {include file="category_templates/input/display_form_field.tpl" id=$fieldInfo.id full_width=true}
                    {/if}
                {/foreach}

                {if isset($form_fields.Price.caption) && !empty($form_fields.Price.caption)}
                    {capture assign="PricePlaceholder"}[[$form_fields.Price.caption]] {$GLOBALS.custom_settings.listing_currency}{/capture}
                    {include file="category_templates/input/display_form_field.tpl" id='Price' placeholder=$PricePlaceholder full_width=true}
                {/if}
            </div>
        </div>
	</fieldset>

	<fieldset>
		<legend>[[Address]]</legend>
        <div class="row">
            <div class="col-sm-11">
                <div class="row">
                    {foreach from=$listingAddressFields item=fieldName}
                        <div class="col-sm-6">
                            {include file="category_templates/input/display_form_field.tpl" id=$fieldName}
                        </div>
                    {/foreach}
                </div>

            </div>
        </div>
	</fieldset>
	{include file="miscellaneous^dialog_window.tpl"}
{/step}

{foreach from=$calendarTypeFieldIds item=fieldId}
	{$fieldCaption = $form_fields.$fieldId.caption}
	{step title=$fieldCaption}
		<fieldset>
			<legend>[[$fieldCaption]]</legend>
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
			        {include file="category_templates/input/display_form_field.tpl" id=$fieldId}
                </div>
            </div>
		</fieldset>
	{/step}
{/foreach}

{step title="Photo & Video"}
    <fieldset>
        <legend>[[Photo]]</legend>
        {module name="classifieds" function="manage_pictures" listing_id=$listing.id}
    </fieldset>

    <fieldset>
        <legend>[[Video]]</legend>
        {if $package.video_allowed}
            {include file="category_templates/input/display_form_field.tpl" id='Video' without_label=true}
        {/if}
        <div class="space-20"></div>
        {if $form_fields.feature_youtube_video_id}
            <div class="hint fieldTypeHint youtubeVideoId bg-info text-center">[[The YouTube video will be displayed if the 'YouTube video' option is activated for this listing]]</div>
            <div class="space-20"></div>
            {include file="category_templates/input/display_form_field.tpl" id='feature_youtube_video_id' full_width=true}
        {/if}
    </fieldset>
{/step}

{step title="Description"}
	<fieldset>
		<legend>[[Description]]</legend>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
		        {include file="category_templates/input/display_form_field.tpl" id='Description' without_label=true}
            </div>
        </div>
	</fieldset>
{/step}
