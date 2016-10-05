{assign var='returnBackExtraParams' value=$GLOBALS.return_back_extra_params|escape:'url'}
{assign var='back' value=$smarty.server.REQUEST_URI|replace:'&':'&amp;'|escape:'url'}
{assign var='xxx' value={page_path module='I18N' function='switch_language'}|cat:"?back="|cat:$back|cat:$returnBackExtraParams}
{i18n->getActiveLanguagesData assign="languages"}
{i18n->getCurrentLanguage assign="currentLanguage"}

<div class="dropdown languageSelector">
    <a id="language-menu" data-toggle="dropdown" href="#">
        <span class="text">
            {$currentLanguage}
        </span>
        <i class="fa fa-chevron-down"></i>
    </a>
    <ul class="dropdown-menu custom dropdown-menu-left" role="menu" aria-labelledby="language-menu">
        {foreach from=$languages item=language name='languages'}
            <li class="language {$language.id} {if $currentLanguage == $language.id}current{/if}">
                {if $currentLanguage == $language.id}
                    <a href="#">
                        {$language.id|strtoupper}
                    </a>
                {else}
                    <a href={$xxx|cat:"&amp;lang="|cat:$language.id}>{$language.id|strtoupper}</a>
                {/if}
            </li>
        {/foreach}
    </ul>
</div>

