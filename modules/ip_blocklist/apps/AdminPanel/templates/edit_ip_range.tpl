<div class="breadcrumbs">
    <ul class="breadcrumb">
        <a href="{page_path module='ip_blocklist' function='blocklist'}">[[IP Blocklist]]</a> &gt; [[Edit IP / IP
        range]]
    </ul>
</div>
<div class="page-content">
    <div class="page-header">
        <h1>[[Edit IP / IP range]]</h1>
    </div>
    <div class="row">
        {display_error_messages}

        {if !$do_not_render_form}
            <div class="alert alert-warning">
                <p>IP, IP range format examples:</p>
                <ul>
                    <li>192.168.1.1 - Single IP address 192.168.1.1</li>
                    <li>192.168.1 - IP range from 192.168.1.0 to 192.168.1.255</li>
                    <li>192.168.1.1/24 - IP range from 192.168.1.0 to 192.168.1.255</li>
                    <li>192.168.1.1/255.255.255.0 - IP range from 192.168.1.0 to 192.168.1.255</li>
                </ul>
            </div>
            <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>)
                are mandatory]]
            </div>
            <form method="post" class="form form-horizontal">
                {CSRF_token}
                <input type="hidden" name="action" value="save_info">
                <input type="hidden" name="sid" value="{$object_sid}">
                {foreach from=$form_fields key=field_id item=form_field}
                    <div class="form-group">
                        <label class="control-label col-sm-3">
                            [[$form_field.caption]]
                            {if $form_field.is_required}<i class="icon-asterisk smaller-60"></i>{/if}
                        </label>

                        <div class="col-sm-6">{input property=$form_field.id}</div>
                    </div>
                {/foreach}
                <div class="clearfix form-actions">
                    <input type="submit" value="[[Save:raw]]" class="btn btn-default">
                </div>
            </form>
        {/if}
    </div>
</div>
