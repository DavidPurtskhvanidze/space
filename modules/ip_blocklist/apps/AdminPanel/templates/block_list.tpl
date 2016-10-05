<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li>[[IP Blocklist]]</li>
  </ul>
</div>
<div class="page-content">
  <div class="page-header">
	  <h1>[[IP Blocklist]]</h1>
  </div>
  <div class="row">

    {module name="ip_blocklist" function="display_blocklist_controls"}

    {if $navgator_filters.filter_error == 'ERROR_INVALID_SEARCH_IP'}
      <p class="text-error">[[Invalid IP / IP Range used for search.]]</p>
    {else}
      <p class="text-error">[[$navgator_filters.filter_error]]</p>
    {/if}

    <div class="alert alert-warning">
      <p>IP, IP range format examples:</p>
      <ul>
        <li>192.168.1.1 - Single IP address 192.168.1.1</li>
        <li>192.168.1 - IP range from 192.168.1.0 to 192.168.1.255</li>
        <li>192.168.1.1/24 - IP range from 192.168.1.0 to 192.168.1.255</li>
        <li>192.168.1.1/255.255.255.0 - IP range from 192.168.1.0 to 192.168.1.255</li>
      </ul>
    </div>
    <div class="space-8"></div>
    <form method="post" name="search_form" class="form-inline">
      {CSRF_token}
      <input type="hidden" name="page_num" value="1" />
      <label class="inline">[[IP / IP Range]] </label>
      <input type="text" value="{$navgator_filters.filter_ip_range}" name="filter_ip_range">
      <input type="submit" value="[[Search]]" class="btn btn-default btn-sm">
    </form>
    <div class="space-8"></div>
    {display_success_messages}

    <div class="alert alert-warning">
      IP addresses and IP address ranges are displayed in accordance with the format prescribed by the
      <a href="http://en.wikipedia.org/wiki/Classless_Inter-Domain_Routing" rel="nofollow" onclick="javascript:window.open(this.href, '_blank'); return false;">CIDR</a> notation.
    </div>

    <div class="row">
      <div class="col-xs-12 IPRange">
        <div class="table-responsive">
          <div class="dataTables_wrapper" role="grid">
            <div class="row">
              <div class="col-sm-6">
                <form method="get" action="">
                  <input type="hidden" name="page_num" value="1" />
                  <span>[[Number of records per page]]</span>
                  <select name="page_rows" onchange="this.form.submit()">
                    <option value="10" {if $navgator_pager.page_rows == 10}selected{/if}>10</option>
                    <option value="20" {if $navgator_pager.page_rows == 20}selected{/if}>20</option>
                    <option value="50" {if $navgator_pager.page_rows == 50}selected{/if}>50</option>
                    <option value="100" {if $navgator_pager.page_rows == 100}selected{/if}>100</option>
                  </select>
                </form>
              </div>
              <div class="col-sm-6 text-right">
                <div class="btn-group text-left">
                  {$url = "?"}
                  {$sortingFields = ['start_ip'=>'IP', 'added'=>'Date added']}
                  {$search = ['sorting_fields'=>$navgator_order]}
                  {include file="miscellaneous^sorting_field_selector.tpl" url=$url search=$search sortingFields=$sortingFields}
                </div>
                <div class="btn-group text-left">
                  <button class="btn btn-primary btn-xs dropdown-toggle actionWithSelected" data-toggle="dropdown">
                    [[Actions with selected]]
                    <i class="icon-angle-down icon-on-right"></i>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a href="{page_path module='ip_blocklist' function='blocklist'}?action=Delete">[[Delete]]</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <form method="post" action="" name="itemSelectorForm">
              {CSRF_token}
              <table class="table table-hover table-striped">
                <thead>
                  <th class="center">
                    <label>
                      <input type="checkbox" id="checkAll" class="ace"/>
                      <span class="lbl"></span>
                    </label>
                  </th>
                <th>[[IP / IP Range]]</th>
                <th>[[Added on]]</th>
                <th>[[Comment:]]</th>
                <th>[[Actions]]</th>
                </thead>
                <tbody>
                {foreach from=$block_list item="ip_record"}
                  <tr>
                    <td class="center">
                      <label>
                        <input class="ace" type="checkbox" name="ip_ranges[{$ip_record.sid}]" value="1" />
                        <span class="lbl"></span>
                      </label>
                    </td>
                    <td>{$ip_record.ip}</td>
                    <td>{$ip_record.added}</td>
                    <td>{$ip_record.comment}</td>
                    <td>
                      <a class="btn btn-info btn-xs edit" href="{page_path module='ip_blocklist' function='edit_ip_range'}?sid={$ip_record.sid}" title="[[Edit:raw]]">
                        <i class="icon-edit"></i>
                      </a>
                      <a class="btn btn-danger btn-xs delete" href="{page_path module='ip_blocklist' function='delete_ip_range'}?sid={$ip_record.sid}" onclick='return confirm("[[Are you sure you want to delete this IP range?:raw]]")' title="[[Delete:raw]]">
                        <i class="icon-trash"></i>
                      </a>
                    </td>
                  </tr>
                {/foreach}
                </tbody>
              </table>
            </form>
            <div class="row">
              <div class="col-sm-6"></div>
              <div class="col-sm-6">
                <div class="dataTables_paginate">
                  <div class="pagination">
                    {if $navgator_pager.page_num-1 > 0}<li class="prev"><a href="?page_num={$navgator_pager.page_num-1}"><i class="icon-double-angle-left"></i></a></li>{/if}
                    {if $navgator_pager.page_num-3 > 0}<li><a href="?page_num=1">1</a></li>{/if}
                    {if $navgator_pager.page_num-3 > 1}<li><a href="#">...</a></li>{/if}
                    {if $navgator_pager.page_num-2 > 0}<li><a href="?page_num={$navgator_pager.page_num-2}">{$navgator_pager.page_num-2}</a></li>{/if}
                    {if $navgator_pager.page_num-1 > 0}<li><a href="?page_num={$navgator_pager.page_num-1}">{$navgator_pager.page_num-1}</a></li>{/if}
                    <li class="active"><a href="#">{$navgator_pager.page_num}</a></li>
                    {if $navgator_pager.page_num+1 <= $navgator_pager.page_total}<li><a href="?page_num={$navgator_pager.page_num+1}">{$navgator_pager.page_num+1}</a></li>{/if}
                    {if $navgator_pager.page_num+2 <= $navgator_pager.page_total}<li><a href="?page_num={$navgator_pager.page_num+2}">{$navgator_pager.page_num+2}</a></li>{/if}
                    {if $navgator_pager.page_num+3 <= $navgator_pager.page_total}<li><a href="#">...</a></li>{/if}
                    {if $navgator_pager.page_num+3 <= $navgator_pager.page_total}<li><a href="?page_num={$navgator_pager.page_total}">{$navgator_pager.page_total}</a></li>{/if}
                    {if $navgator_pager.page_num+1 <= $navgator_pager.page_total}<li class="next"><a href="?page_num={$navgator_pager.page_num+1}"><i class="icon-double-angle-right"></i></a></li>{/if}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
	{require component="jquery" file="jquery.js"}
	<script type="text/javascript">
		var noSelectedItemsMessage = "[[You have not selected any items. Please select one or more items and proceed with actions.:raw]]";
		{literal}
		$(document).ready(function(){
			$(".searchResultControls .actionList a").click(function(){
				window.location.href = $(this).attr("href") + "&" + $("form[name='itemSelectorForm']").serialize();
				return false;
			});

      $('table th input:checkbox').on('click' , function(){
        var that = this;
        $(this).closest('table').find('tr > td:first-child input:checkbox')
            .each(function(){
              this.checked = that.checked;
              $(this).closest('tr').toggleClass('selected');
            });
      });

			$(".actionWithSelected").click(function(){
				if (!$('input[name^=ip_ranges]:checked').length)
				{
					$(this).addClass("disabled");
					alert(noSelectedItemsMessage);
				}
				else
					$(this).removeClass("disabled");
			});
      $('.table tr input:checkbox').on('change', function(){
        if($(this).prop("checked")){
          $(".actionWithSelected").removeClass("disabled");
        }
      });
		});
		{/literal}
	</script>
	{include file="miscellaneous^multilevelmenu_js.tpl"}
</div>
