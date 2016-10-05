<form class="navbar-form navbar-right" action="{page_path module='I18N' function='switch_language'}" role="form">
    <div class="form-group">
      <input type="hidden" value="{$smarty.server.REQUEST_URI|replace:'&':'&amp;'}" name="back" />
      <select name="lang" onchange="this.form.submit()" class="form-control">
          {i18n->getActiveLanguagesData assign="languages"}
          {i18n->getCurrentLanguage assign="currentLanguage"}
          {foreach from=$languages item=language}
            <option value="{$language.id}"{if $language.id == $currentLanguage} selected="selected"{/if}>{$language.caption}</option>
          {/foreach}
      </select>
    </div>
</form>
