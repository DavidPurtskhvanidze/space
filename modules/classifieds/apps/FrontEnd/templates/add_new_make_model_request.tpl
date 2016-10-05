<div class="contactFormPage addNewMakeModelRequest">
{if $message_sent == false}
    {if $noCommentError}
        <p class="error">[[Please include your comments]].</p>
    {/if}
    <form method="post" action="">
        <div class="contactForm">
            {CSRF_token}
            <input type="hidden" name="action" value="send_message"/>
            <div class="reportFormComments">
                <b>[[Comments]]</b><br/>
                {if $GLOBALS.settings.enable_wysiwyg_editor}
                    {WYSIWYGEditor type="ckeditor" name="comments" width="100%" height="150px" ToolbarSet="Tiny"}
                        {$comments}
                    {/WYSIWYGEditor}
                    {else}
                    <textarea cols="20" rows="5" name="comments" class="form-control">{$comments}</textarea>
                {/if}
            </div>
            <input type="submit" value="[[Submit:raw]]"/>
        </div>
    </form>
    {else}
    <p>[[Thank you very much for your message. We will respond to you as soon as possible.]]</p>
{/if}
</div>
