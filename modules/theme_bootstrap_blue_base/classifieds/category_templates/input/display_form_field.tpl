{if empty($placeholder)}
	{capture assign='placeholder'}{tr domain="FormFieldCaptions"}{$form_fields.$id.caption}{/tr}{/capture}
{/if}

<div class="form-group row">
    {if !$without_label}
        <label class="{if $full_width} col-md-2 {else} col-md-4 {/if}text-right control-label">
            {if $label}
                {tr domain="FormFieldCaptions"}{$label}{/tr}
            {else}
                {$placeholder}
            {/if}
            {if $form_fields.$id.is_required}
                <span class="asterisk">*</span>
            {/if}
        </label>
        <div class="{if $full_width} col-md-10 {else} col-md-8{/if}">
            {input property=$id template=$template}
        </div>
    {else}
        <div class="col-xs-12">
            {input property=$id template=$template}
        </div>
    {/if}
</div>
