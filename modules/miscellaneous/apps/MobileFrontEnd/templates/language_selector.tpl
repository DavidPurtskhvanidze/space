{assign var='xxx' value={page_path module='I18N' function='switch_language'}|cat:"?back="|cat:$smarty.server.REQUEST_URI|replace:'&':'&amp;'}
<span class="languageSelector">
	{i18n->getActiveLanguagesData assign="languages"}
	{i18n->getCurrentLanguage assign="currentLanguage"}
	{foreach from=$languages item=language name='languages'}
	<span class="language {$language.id}">
		{if $currentLanguage == $language.id}
			{$language.id}
		{else}
			<a href={$xxx|cat:"&lang="|cat:$language.id}>{$language.id}</a>
		{/if}
	</span>
	{/foreach}
</span>
