{assign var='returnBackExtraParams' value=$GLOBALS.return_back_extra_params|escape:'url'}
{assign var='back' value=$smarty.server.REQUEST_URI|replace:'&':'&amp;'|escape:'url'}
{assign var='xxx' value=$back|cat:$returnBackExtraParams}
<form action="{page_path module='I18N' function='switch_language'}">
    <div class="languageSelector">
    <input type="hidden" value="{$xxx}" name="back" />
    [[Choose language]]
    <select name="lang" onchange="this.form.submit()" class="form-control">
        {i18n->getActiveLanguagesData assign="languages"}
        {i18n->getCurrentLanguage assign="currentLanguage"}
        {foreach from=$languages item=language}
        <option value="{$language.id}"{if $language.id == $currentLanguage} selected="selected"{/if}>{$language.caption}</option>
        {/foreach}
    </select>
    </div>
</form>
