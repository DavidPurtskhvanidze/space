<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li>[[Module Templates]]</li>
  </ul>
</div>

<div class="page-content">
  <div class="page-header">
    <h1>[[Modules]]</h1>
  </div>

  <div class="row">
    <table class="table table-striped table-hover">
      <thead>
        <tr class="head">
          <th>[[Module Name]]</th>
          <th>[[Description]]</th>
          <th>[[Actions]]</th>
        </tr>
      </thead>
      <tbody>
        {foreach from=$moduleTemplateProviders item="provider"}
          <tr class="{cycle values="odd,even"}">
            <td>[[{$provider->getModuleTemplateProviderName()}]]</td>
            <td>[[{$provider->getModuleTemplateProviderDescription()}]]</td>
            <td>
              <a class="edit btn btn-xs btn-info" href="?application_id={$appId}&amp;moduleTemplateProviderId={$provider->getId()|escape}" title="[[Edit:raw]]">
                <i class="icon-edit bigger-110"></i>
              </a>
            </td>
          </tr>
        {/foreach}
      </tbody>
    </table>
  </div>
</div>
