<div class="contactFormPage">
        <div class="container">
            {i18n->getCurrentLanguage assign="currentLanguage"}
            <div class="space-20"></div>
            <div class="space-20"></div>
            {module name="static_content" function="show_static_content" pageid='Contact_'|cat:$currentLanguage}
            {display_error_messages}
            {if $message_sent == true}
                <div class="alert alert-success">
                    <p>[[Thank you very much for your message. We will respond to you as soon as possible.]]</p>
                    {if $returnBackUri}
                        {assign var='backToListingLink' value=$GLOBALS.site_url|cat:$returnBackUri}
                        [[Click
                        <a href="$backToListingLink">here</a>
                        to go back to listing.]]
                    {/if}
                </div>
            {/if}
            <div class="space-20"></div>
        </div>
        <div class="bg-grey">
            <div class="container">
                <div class="space-20"></div>
                <div class="space-20"></div>
                <div class="space-20"></div>
                <div class="row">
                    <div class="contactForm col-md-6">
                        <form method="post" action="" class="form-horizontal">
                            <input type="hidden" name="action" value="send_message"/>
                            <input type="hidden" name="returnBackUri" value="{$returnBackUri}"/>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">[[Name]]{if $form_fields.name.is_required}<span class="asterisk">*</span>{/if}</label>
                                        <div class="col-md-9">
                                            {capture assign="defaultName"}{if $GLOBALS.current_user.logged_in}{$GLOBALS.current_user.FirstName} {$GLOBALS.current_user.LastName}{/if}{/capture}
                                            {input property="name" default=$defaultName}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">[[Email]]{if $form_fields.email.is_required}<span class="asterisk">*</span>{/if}</label>
                                        <div class="col-md-9">
                                            {capture assign="defaultEmail"}{if $GLOBALS.current_user.logged_in}{$GLOBALS.current_user.email}{/if}{/capture}
                                            {input property="email" default=$defaultEmail}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">[[Comments]]{if $form_fields.comments.is_required}<span class="asterisk">*</span>{/if}</label>
                                <div class="col-md-9">{input property="comments"}</div>
                            </div>
                            {if isset($form_fields.captcha)}
                                <div class="row">
                                    <div class="col-md-12 col-sm-6 col-sm-offset-3 col-md-offset-0">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">[[Enter a code from the image below]] <span class="asterisk">*</span></label>
                                            <div class="col-md-9">{input property="captcha"}</div>
                                        </div>
                                    </div>
                                </div>
                            {/if}
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-9 text-sm-center text-md-right">
                                    <input  class="btn btn-orange h5" type="submit" value="[[Submit:raw]]"/>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="contactDetailsWrapper col-md-6">
                        <div class="row">
                            <div class="col-md-9 col-md-offset-2 col-sm-12 col-sm-offset-0">
                                <div class="contactDetails">
                                    <h3 class="h4 bordered">[[Contact Details]]</h3>
                                    <address class="text-center">
                                        <p>
                                            <i class="fa fa-phone"></i>&nbsp;&nbsp;&nbsp;
                                            <span class="telephone grey-text">[[0123 456 7890]]</span>
                                        </p>

                                        <p>
                                            <i class="fa fa-envelope-o"></i>&nbsp;&nbsp;&nbsp;
                                            <a class="email" href="mailto:{$systemEmail}" title="[[Contact us by email]]">[[$systemEmail]]</a>
                                        </p>
                                    </address>
                                    <hr/>
                                    <div class="space-20"></div>
                                    <div class="row schedule">
                                        <div class="col-xs-6 text-right">[[Monday - Friday]]</div>
                                        <div class="col-xs-6 orange">[[9.00am - 6.00pm]]</div>
                                    </div>
                                    <div class="row schedule">
                                        <div class="col-xs-6 text-right">[[Saturday]]</div>
                                        <div class="col-xs-6 orange">[[9.00am - 5.00pm]]</div>
                                    </div>
                                    <div class="row schedule">
                                        <div class="col-xs-6 text-right">[[Sunday]]</div>
                                        <div class="col-xs-6 orange">[[10.00am - 4.00pm]]</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space-20"></div>
                <div class="space-20"></div>
                <div class="space-20"></div>
            </div>
        </div>
</div>

<script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>
