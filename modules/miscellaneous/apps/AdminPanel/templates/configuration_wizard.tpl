<script type="text/javascript">
  var current_step;
</script>
<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li>[[Configuration Wizard]]</li>
  </ul>
</div>

<div class="page-content">
  <div class="page-header">
    <h1>[[Configuration Wizard]]</h1>
  </div>

  <div class="row">
    {display_success_messages}
    {display_error_messages}

    {if $watermarkUploadStatus == 'CANT_MOVE_UPLOADED_FILE'}
      <p class="text-error">[[Cannot move uploded file. Please set file permissions to 755.]]</p>
    {elseif $watermarkUploadStatus == 'UNSUPPORTED_FILE_TYPE' || $watermarkUploadStatus == 'RESTRICTED_EXTENSION'}
      <p class="text-error">[[Supported file formats : JPEG, GIF, PNG8<br />Please refer to the article of the User Manual at User Manual -> Additional Features -> Watermark to learn more about watermark settings.]]</p>
    {elseif $watermarkUploadStatus == 'LARGER_THAN_INI_SIZE' || $watermarkUploadStatus == 'LARGER_THAN_FORM_SIZE'}
      <p class="text-error">[[The uploaded file is too large. Please either make the file smaller, or increase the size limit for uploads.]]</p>
    {elseif $watermarkUploadStatus == 'NO_TMP_DIR' || $watermarkUploadStatus == 'CANT_WRITE_TO_TMP_DIR'}
      <p class="text-error">[[Temporary directory cannot be found or is not writable. Please report this error to the server administrator.]]</p>
    {/if}

    <div class="row">
      <div class="col-md-4">
        <ul class="list-group" id="stepsLinks">
          {*  createNavigationPanel() *}
        </ul>
      </div>

      <form id="wizardForm" class="col-md-8" enctype="multipart/form-data" method="post">
        {CSRF_token}
        <input type="hidden" name="action" value="save"/>
        <input type="hidden" name="current_step" value="{$current_step}"/>
        <div class="content">
          {* displayStep() *}
        </div>
        <div class="clearfix form-actions ClearBoth">
          <div class="pull-right">
            <button class="btn btn-default" id="skipButton">[[Skip]]</button>
            <input type="submit" class="btn btn-default" value="[[Next]]"/>
          </div>
        </div>
      </form >

      <div id="stepsContent">
        {* hidden block *}
        {$step = 1}
        {foreach from=$pages item=page}
          <div class="panel panel-default step{$step}">
            <div class="panel-heading" data-step="{$step}">
              {$step++}. [[{$page->getCaption()}]]
            </div>
            <div class="alert alert-info">
              [[If you are not sure what to specify on this step, you can skip it and return to it later using this wizard or a standard configuration interface of the Admin Panel.]]
            </div>
            <div class="panel-body">
              {$page->getContent()}
            </div>
          </div>
        {/foreach}
        <div class="endStep">
          <h2>Congratulations!</h2>
          <span>
            You have finished your site's general configuration
          </span>
        </div>
    </div>
        
    {require component="jquery" file="jquery.js"}
    {require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
    {require component="jquery-cookie" file="jquery.cookie.js"}
    <script type="text/javascript">
      function createNavigationPanel()
      {
        $('#stepsContent div.panel-heading').each(function(){
          $('#stepsLinks').append('<li class="list-group-item step' + $(this).data('step') + '" data-step="' + $(this).data('step') + '">' + $(this).text() + '</li>');
        });
      }

      function displayStep(step)
      {
        if(typeof(step) == "undefined") return displayStep(1);
        if($('#stepsContent div.panel-default.step' + step).length == 0)
        {
          $('#wizardForm').parent().html($('.endStep').html());
          return;
        }
        current_step = step;
        $("input[name='current_step']").val(step);
        $('#wizardForm .content').html($('#stepsContent div.panel-default.step' + step).clone(true));
        $('#stepsLinks li.list-group-item.active').removeClass('active');
        $('#stepsLinks li.list-group-item.step' + step).addClass('active');
        $('#wizardForm input[type="file"]').ace_file_input({
          no_file:'[[No File ...]]',
          btn_choose:'[[Choose]]',
          btn_change:'[[Change]]',
          droppable:false,
          onchange:null,
          icon_remove: false,
          thumbnail:false //| true | large
        });
      }

      $(document).ready(function(){
        createNavigationPanel();
        displayStep({$current_step});
        $('#stepsLinks li.list-group-item').on('click', function(){
          displayStep($(this).data('step'))
        });

        $('button#skipButton').on('click',function(){
          current_step++;
          displayStep(current_step);
          return false;
        });
      });
    </script>

    
  </div>
</div>
