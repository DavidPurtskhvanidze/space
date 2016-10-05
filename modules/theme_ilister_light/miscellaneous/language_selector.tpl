{assign var='returnBackExtraParams' value=$GLOBALS.return_back_extra_params|escape:'url'}
{assign var='back' value=$smarty.server.REQUEST_URI|replace:'&':'&amp;'|escape:'url'}
{assign var='xxx' value={page_path module='I18N' function='switch_language'}|cat:"?back="|cat:$back|cat:$returnBackExtraParams}
<div class="languageSelector">
	{i18n->getActiveLanguagesData assign="languages"}
	{i18n->getCurrentLanguage assign="currentLanguage"}
	{foreach from=$languages item=language name='languages' name=languages_block}
	<span class="language {$language.id} {if $currentLanguage == $language.id}current{/if}">
		{if $currentLanguage == $language.id}
			{$language.caption}
		{else}
			<a href={$xxx|cat:"&amp;lang="|cat:$language.id}>{$language.caption}</a>
		{/if}
	</span>
	{if !$smarty.foreach.languages_block.last}<span class="pipe">|</span>{/if}
	{/foreach}
</div>
