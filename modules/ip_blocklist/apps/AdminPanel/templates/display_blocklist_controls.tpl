{if $blockListControls}
	<div class="ipBlockListControls">
		<ul class="list-unstyled">
			{foreach from=$blockListControls item='control'}
				<li>
					{if $control.template}
						{module name="main" function="display_template" template=$control.template template_params=$control.templateParams}
					{else}
						<a class="btn btn-link" href="{if !$control.absoluteUrl}{$GLOBALS.site_url}{/if}{$control.url}">[[$control.caption]]</a>
					{/if}
				</li>
			{/foreach}
		</ul>
	</div>
{/if}
