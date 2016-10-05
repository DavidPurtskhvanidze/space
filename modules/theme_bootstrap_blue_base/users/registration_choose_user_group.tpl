<h1 class="page-title">[[Registration]]</h1>
<div class="space-20"></div>
<p>[[Please select the appropriate user group]]:</p>
<div class="space-20"></div>
<div class="row">
    {foreach from=$user_groups_info item=user_group_info}
        <div class="col-md-1 visible-lg visible-md"></div>
        <div class="col-md-3">
            <a class="user-group-id" href="?user_group_id={$user_group_info.id}">[[PhrasesInTemplates!{$user_group_info.name}]]</a>
            <div class="space-20"></div>
            <div class="grey-text">[[PhrasesInTemplates!{$user_group_info.description}]]</div>
        </div>
    {/foreach}
</div>
