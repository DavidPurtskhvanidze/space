<div id="sendLetterForm" class="sendLetterForm modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form class="form form-horizontal" method="post">
                    {CSRF_token}
                    <div class="form-group">
                        <label for="subject" class="col-sm-3">[[Subject]]</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="subject" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="letter_body" class="col-sm-3">[[Body]]</label>

                        <div class="col-sm-9">
                            {if $GLOBALS.settings.enable_wysiwyg_editor}
                                {$height='300px'}
                                <div class="form-control-group {if $hasError}has-error tooltip-error{/if}"
                                     {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if}
                                     style="display:inline-block">
                                    {WYSIWYGEditor type="ckeditor" name="letter_body" width="$width" height="$height" ToolbarSet="FullNoForms" entities=false entities_latin=false ForceSimpleAmpersand=true}{/WYSIWYGEditor}
                                </div>
                            {else}
                                <textarea name="letter_body"
                                          class="form-control {if $hasError}has-error tooltip-error{/if}"
                                          {if $hasError}data-rel="tooltip" data-placement="top"
                                          title="{$error}"{/if}></textarea>
                            {/if}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-9">
                            <input type="hidden" name="users[{$user.sid}]" value="{$user.sid}">
                            <input type="submit" value="[[Submit:raw]]" class="btn btn-default">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
