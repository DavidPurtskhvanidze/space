<div class="breadcrumbs">
  <ul class="breadcrumb">
	  <li><a href="?application_id={$appId}">[[Module Templates]]</a> &gt; <a href="?application_id={$appId}&amp;moduleTemplateProviderId={$moduleTemplateProvider->getId()}">[[{$moduleTemplateProvider->getModuleTemplateProviderName()}]]</a></li>
	  <li>{$templateName}</li>
  </ul>
</div>

<div class="page-content">
  <div class="page-header">
    <h1>[[Edit Template]]</h1>
  </div>

  <div class="row">
    {include file="errors.tpl"}
    {display_success_messages}
    <div class="editTemplate">

      <ul class="list-unstyled">
        <li>[[Active Theme]]: <b>{$currentThemeName}</b></li>
        <li>[[Module]]: <b>[[{$moduleTemplateProvider->getModuleTemplateProviderName()}]]</b>          </li>
        <li>[[Template]]: <b>{$templateName}</b></li>
      </ul>

      <div class="space-8"></div>

      {include file="edit_template_form.tpl"}
    </div>
  </div>
</div>
