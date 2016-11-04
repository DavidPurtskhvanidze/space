{if empty($placeholder)}
	{capture assign='placeholder'}{tr domain="FormFieldCaptions"}{$form_fields.$id.caption}{/tr}{/capture}
{/if}

<div class="form-group">
	{if $center}
		<div class="col-sm-8 col-sm-offset-2">
			<div class="input-group">
                {input property=$id placeholder=$placeholder template=$template}
				<span class="input-group-addon">
                    <span class="asterisk">
                        {if $form_fields.$id.is_required}
                            *
                        {else}
                            &nbsp;
                        {/if}
                    </span>
                </span>
			</div>
		</div>
	{elseif $form_fields.$id.is_required}
		<div class="col-sm-12">
            <div class="input-group">
                {input property=$id placeholder=$placeholder}
                <span class="input-group-addon"><span class="asterisk">*</span></span>
            </div>
		</div>
	{else}
		<div class="col-sm-12">
			{input property=$id placeholder=$placeholder}
		</div>
	{/if}
</div>
