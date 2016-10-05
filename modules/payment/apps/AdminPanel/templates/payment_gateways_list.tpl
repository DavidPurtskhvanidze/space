<div class="paymentGateways">
	<div class="breadcrumbs">
    <ul class="breadcrumb">
      [[Payment Gateways]]
    </ul>
</div>

 <div class="page-content">
   <div class="page-header">
	  <h1>[[Payment Gateways]]</h1>
   </div>

   <div class="row">
    {display_error_messages}
    <div class="col-sm-6">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>[[Name]]</th>
            <th>[[Actions]]</th>
          </tr>
        </thead>
        <tbody>
          {foreach from=$gateways item="gateway"}
            <tr>
              <td>[[{$gateway->getCaption()}]]</td>
              <td>
                {if $gateway->getHasDataToStore()}
                  <a class="edit btn btn-xs btn-inverse" href="{page_path module='payment' function='configure_gateway'}?gatewayClassname={$gateway|get_class|escape}" title="[[Configure:raw]]">
                    <i class="icon-cogs bigger-110"></i>
                  </a>
                {/if}
                {if $gateway|is_a:'apps\FrontEnd\IHaveTemplate'}
                  <a class="edit btn btn-xs btn-info" href="{page_path id='edit_templates'}?moduleTemplateProviderId={$gateway->getModuleTemplateProviderId()|escape}&amp;template={$gateway->getTemplateName()|escape}" title="[[Edit template:raw]]">
                    <i class="icon-edit bigger-110"></i>
                  </a>
                {/if}
              </td>
            </tr>
          {/foreach}
        </tbody>
      </table>
    </div>
   </div>
 </div>
</div>
