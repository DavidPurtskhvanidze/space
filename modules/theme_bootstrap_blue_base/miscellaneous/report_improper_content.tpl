<div class="reportImproperContentPage">

    <h1>[[Report Inappropriate Content]]</h1>

    {if $message_sent}
        <p class="alert alert-success">
            <span>[[Your message was sent.]]</span>
        </p>
    {elseif !empty($errors)}
        {include file="errors.tpl"}
    {else}
        <div class="description">
            [[If you find the content of the {$objectType} $objectId inappropriate, and believe it should be removed from our website, please let us know that by filling out the form below. Your inputs will be sent to the administrator for review and action.]]
        </div>

        {display_error_messages}

        <div class="reportImproperForm">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-7">
                    <div class="thumbnail reportImproperFormInner">
                        <p class="alert alert-warning">[[Fields marked with (<span class="asterisk">*</span>) are mandatory]]</p>
                        <form action="" method="post" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">[[$form_fields.FullName.caption]] {if $form_fields.FullName.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</label>
                                <div class="col-sm-9">{input property="FullName"}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">[[$form_fields.Email.caption]] {if $form_fields.Email.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</label>
                                <div class="col-sm-9">{input property="Email"}</div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">[[$form_fields.Report.caption]] {if $form_fields.Report.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</label>
                                <div class="col-sm-9">{input property="Report"}</div>
                            </div>
                            {if isset($form_fields.captcha)}
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">[[$form_fields.captcha.caption]] {if $form_fields.captcha.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</label>
                                    <div class="col-sm-9">{input property="captcha"}</div>
                                </div>
                            {/if}
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9">
                                    <input type="hidden" name="action" value="report" />
                                    <input type="hidden" name="objectType" value="{$objectType}" />
                                    <input type="hidden" name="objectId" value="{$objectId}" />
                                    <input type="hidden" name="returnBackUri" value="{$returnBackUri}" />
                                    <input type="submit" value="[[Send:raw]]" class="contactButton btn btn-orange h6" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    {/if}

    <br />
    {assign var='backToListingLink' value=$GLOBALS.site_url|cat:$returnBackUri}
    {if $message_sent or !empty($errors)}
        [[Click <a href="$backToListingLink">here</a> to go back to listing.]]
    {/if}
    <script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>
</div>
