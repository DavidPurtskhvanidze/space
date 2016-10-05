<div class="addPhrase">
	   
    <div class="breadcrumbs">
        <ul class="breadcrumb">
            <li><a href="{page_path module='I18N' function='manage_phrases'}">[[Manage Phrases]]</a> &gt; [[Add phrase]]</li>
        </ul>
    </div>
<div class="page-content">
        <div class="page-header">
            <h1 class="lighter">[[Add phrase]]</h1>
        </div>
<div class="row">
    <div class="searchForm">
	{display_error_messages}

	<div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>) are mandatory]]</div>

    <form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
        {CSRF_token}
		<input type="hidden" name="action" value="add_phrase">
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[Phrase ID]]
              <i class="icon-asterisk smaller-60"></i>
            </label>
            <div class="col-sm-8">
                <input type="text" name="phrase" value="{$request_data.phrase|escape}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">
              [[Domain]]
              <i class="icon-asterisk smaller-60"></i>
            </label>
            <div class="col-sm-8">
                <select id="DomainSelectorControl" name="domain" class="form-control">
                    {foreach from=$domains item=domain}
                        <option value="{$domain}">{$domain}</option>
                    {/foreach}
                </select>
                <input id="NewDomainControl" type="text" name="domain" value="" class="form-control">
                <a id="DomainControlSwitch" href="#">DomainControlSwitch</a>
            </div>
        </div>
        {foreach from=$langs item=lang}
            <div class="form-group">
                <label class="col-sm-2 control-label">
                  {$lang.caption}
                </label>
                {assign var="lang_id" value=$lang.id}
                <div class="col-sm-8">
                    <textarea name="translations[{$lang.id}]" class="form-control">{$request_data.translations.$lang_id|escape}</textarea>
                </div>
            </div>
        {/foreach}
        <div class="clearfix form-actions">
            <input type="submit" value="[[Save:raw]]" class="btn btn-default">
          </div>
	</form>
</div>
</div>
</div>
</div>
{require component="jquery" file="jquery.js"}
<script type="text/javascript">
	var newDomainLabel = "[[Add new domain:raw]]";
	var selectDomainLabel = "[[Select existing domain:raw]]";
	var selcetedDomain = "{$request_data.domain}";
	
	function toggleDomainControl(init)
	{
		var label;
		var flag;

		if ($('#DomainControlSwitch').html() == selectDomainLabel || init)
		{
			label = newDomainLabel;
			flag = true;
		}
		else
		{
			label = selectDomainLabel;
			flag = false;
		}

		$('#DomainControlSwitch').html(label);
		$('#NewDomainControl').prop('disabled', flag);
		$('#NewDomainControl').css('display', flag ? 'none' : '');

		$('#DomainSelectorControl').prop('disabled', !flag);
		$('#DomainSelectorControl').css('display', !flag ? 'none' : '');
	}

	$(document).ready(function() {
		var selectMode = false;

		if (selcetedDomain == '')
		{
			selectMode = true;
		}
		else
		{
			$('#DomainSelectorControl option').each(function() {
				if ($(this).attr('value') == selcetedDomain)
				{
					selectMode = true;
					$(this).attr('selected', 'selected');
				}
			});
			if (!selectMode)
			{
				$('#NewDomainControl').attr('value', selcetedDomain);
			}
		}

		$('#DomainControlSwitch').bind('click', function() {
			toggleDomainControl();
			return false;
		});

		toggleDomainControl(selectMode);
	});
</script>
