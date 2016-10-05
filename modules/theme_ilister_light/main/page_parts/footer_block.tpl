<div id="PageFooter" class="colorize-footer">
	{if $GLOBALS.mobile_front_end_url}
		<div class="linkToMobileSite colorize-footer">
			<a href="{$GLOBALS.mobile_front_end_url}"><img class="linkIcon" src="{url file='main^icons/smartphone.png'}" alt="&#8226;" /></a>
			<a href="{$GLOBALS.mobile_front_end_url}">[[Mobile Website]]</a>
		</div>
	{/if}
		<div class="copyrights colorize-footer">
            {IncludeCopyright}
		</div>
	{include file="miscellaneous^share_section.tpl"}
</div>
