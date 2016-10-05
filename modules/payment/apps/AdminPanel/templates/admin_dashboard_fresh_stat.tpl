{if $count == 0}0{else}<a href="{page_path module='payment' function='payments'}?action=search&status[equal]=Completed&creation_date[not_earlier]={$date}">{$count}</a>{/if}
