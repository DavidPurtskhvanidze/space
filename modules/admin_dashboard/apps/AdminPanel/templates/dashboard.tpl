{assign var="currentVersion" value=$GLOBALS.settings.product_version}
{assign var="productName" value=$GLOBALS.settings.product_name}

<div class="dashboard page-content">
  <div class="page-header">
	  <h1>[[Dashboard]]</h1>
  </div>
	{display_error_messages}
	<br />

	{if $newestVersion}
		<div class="productVersion hint">
			{if $upToDate}
				[[The $productName version installed on your website is up to date.]]
			{else}
				{capture assign="currentVersionLink"}{$frontEndUrl}/packageinfo.txt{/capture}
				{capture assign="newestVersionLink"}{$changelogUrl}{/capture}
				{capture assign="upgradeRequestLink"}http://www.worksforweb.com/company/contact-us/support-ticket/{/capture}
				[[Your website version is <a href="$currentVersionLink" target="_blank">$currentVersion</a>. There is a new version of $productName &mdash; <a href="$newestVersionLink" target="_blank">$newestVersion</a>. If you like you can <a href="$upgradeRequestLink" target="_blank">request an upgrade</a>.]]
			{/if}
		</div>
	{/if}

	{if $freshStats|iterator_count > 0}
		<div class="freshStats row">
      <div class="col-sm-12">
        <div class="widget-box transparent">
          <div class="widget-header widget-header-flat">
            <h2 class="lighter">
              <i class="icon-signal"></i>
              [[Fresh Stats]]
            </h2>
          </div>
          <div class="widget-body">
            <div class="widget-body-inner">
              <div class="widget-main no-padding">
                <table class="items table table-striped table-hover">
                  <thead class="thin-border-bottom">
                    <tr>
                      <th class="forTheLastHeader">[[For the last]]:</th>
                      <th>[[day]]</th>
                      <th>[[forAWeek]]</th>
                      <th>[[month]]</th>
                    </tr>
                  </thead>
                  <tbody>
                  {foreach from=$freshStats item=freshStat}
                    <tr class="freshStat{$freshStat.caption|replace:" ":""}">
                      <th>[[$freshStat.caption]]</th>
                      <td class="forLastDay">{$freshStat.forLastDay}</td>
                      <td class="forLastWeek">{$freshStat.forLastWeek}</td>
                      <td class="forLastMonth">{$freshStat.forLastMonth}</td>
                    </tr>
                  {/foreach}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
		</div>
	{/if}

  {if $statBlocks|iterator_count > 0}
    <div class="row">
      {foreach from=$statBlocks item=statBlock}
      <div class="col-sm-4 {$statBlock.divClass}">
        <div class="widget-box">
          <div class="widget-header widget-header-flat">
            <h4 class="lighter">
              <i class="icon-signal"></i>
              [[$statBlock.caption]]
            </h4>
          </div>
          <div class="widget-body">
            <div class="widget-body-inner">
              <div class="widget-main no-padding">
                {$statBlock.content}
              </div>
            </div>
          </div>
        </div>
      </div>
      {/foreach}
    </div>
  {/if}

	<div class="taskSchedulerStats row">
    <div class="col-sm-4">
      <div class="widget-box">
        <div class="widget-header header-color-dark">
          <h4 class="lighter">
            <i class="icon-tasks"></i>
            [[Task Scheduler]]
          </h4>
        </div>
        <div class="widget-body">
          <div class="widget-toolbox">
            <div class="btn-toolbar">
              {capture assign="returnBackUri"}{page_path id='root'}{/capture}
              <div class="btn-group">
                <a class="btn btn-link btn-sm" target="_blank" href="{page_path module='miscellaneous' function='task_scheduler' app='FrontEnd'}?showlog&amp;returnBackUri={$returnBackUri|urlencode}">[[Run Task Scheduler]]</a>
                <a class="btn btn-link btn-sm" target="_blank" href="{$frontEndUrl}/{$taskSchedulerLogFilename}">[[Task Scheduler Log File]]</a>
              </div>
            </div>
          </div>
          <div class="widget-main padding-16">
            [[Task Scheduler last execution date:]] {$taskSchedulerLastExecutedDate}
          </div>
        </div>
      </div>
    </div>
	</div>

	<div class="newsAndPublicationsBlock row">
    <div class="col-sm-12">
      <div class="widget-box transparent">
        <div class="widget-header">
          <h3 class="lighter">
            <i class="icon-pencil"></i>
            [[WorksForWeb News]]
          </h3>
        </div>
        <div class="widget-body">
          <div class="widget-main padding-6 no-padding-left no-padding-right">
            <ul class="list-group list-unstyled">
            {foreach from=$news item="newsItem"}
              <li class="list-group-item no-border">
                <span class="newsTitle"><a href="{$newsItem.link}" target="_blank">{$newsItem.title}</a></span>
                <span class="posted">{tr type=date}{$newsItem.pubDate}{/tr}</span>
                <div class="newsDescription">{$newsItem.description|strip_tags|truncate:140}</div>
              </li>
            {/foreach}
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="space-8"></div>

    <div class="col-sm-12">
      <div class="widget-box transparent">
        <div class="widget-header">
          <h3 class="lighter">
            <i class="icon-book"></i>
            [[WorksForWeb Publications]]
          </h3>
        </div>
        <div class="widget-body">
          <ul class="list-group list-unstyled">
          {foreach from=$publications item="publicationsItem"}
            <li class="list-group-item no-border">
              <span class="newsTitle"><a href="{$publicationsItem.link}" target="_blank">{$publicationsItem.title}</a></span>
              <span class="posted">{tr type=date}{$publicationsItem.pubDate}{/tr}</span>
              <div class="newsDescription">{$publicationsItem.description|truncate:140}</div>
            </li>
          {/foreach}
          </ul>
        </div>
      </div>
    </div>
	</div>
</div>
