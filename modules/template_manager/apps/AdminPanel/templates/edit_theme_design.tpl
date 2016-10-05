<div class="breadcrumbs">
    <ul class="breadcrumb">
        <a href="{page_path id='edit_themes'}">[[Themes]]</a> &gt; {$file}
    </ul>
</div>

<div class="page-content">
    <div class="page-header">
        <h1>[[Edit $file]]</h1>
    </div>

    <div class="row">
        {display_error_messages}
        {display_success_messages}

        <div class="editTemplate">

            <ul class="list-unstyled">
                <li>[[Theme]]: <b>{$themeName}</b></li>
                <li>[[Module]]: <b>[[Main]]</b></li>
            </ul>

            {if $fileIsEditable}
                <form action="" method="post" class="form">
                    {CSRF_token}
                    <input type="hidden" name="application_id" value="{$appId}">
                    <input type="hidden" name="theme" value="{$themeName}">
                    <input type="hidden" name="action" value="save">

                    <div class="template">
                        <div class="form-group">
                            <textarea name="design_content" id="design_content"
                                      class="form-control">{$fileContent|escape}</textarea>
                        </div>
                        <input type="submit" value="[[Save:raw]]" class="btn btn-default">
                    </div>
                </form>
            {else}
                <div class="template">
                    <textarea name="design_content" id="design_content" disabled="disabled"
                              class="form-control">{$fileContent|escape}</textarea>
                </div>
            {/if}
        </div>

        {require component="codemirror" file="lib/codemirror.js"}
        {require component="codemirror" file="lib/codemirror.css"}
        {require component="codemirror" file="mode/css/css.js"}
        {if $file eq 'design.scss'}
            {require component="codemirror" file="mode/sass/sass.js"}
        {/if}

        <script>
            $(function () {
                var readOnly = false;
                var disabled = $('#design_content').attr('disabled');
                if (disabled == 'disabled')
                    var readOnly = 'nocursor';
                var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('design_content'), {
                    lineNumbers: true,
                    lineWrapping: true,
                    readOnly: readOnly
                });
            });
        </script>
    </div>
</div>
