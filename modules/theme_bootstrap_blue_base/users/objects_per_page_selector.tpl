<div class="dropdown ObjectsPerPageSelector">
    {$recordPerPageValueList = [10,20,50,100]}
    <span class="label">[[records per page]]</span> <br/>
    <a id="ObjectsPerPageSelector" data-toggle="dropdown" href="#">
        {$search.objects_per_page}  <i class="fa fa-chevron-down pull-right"></i>
    </a>
    <ul class="dropdown-menu" role="menu" aria-labelledby="SortingFieldSelector">
        {foreach $recordPerPageValueList as $recordPerPage}
            <li role="presentation" {if $search.objects_per_page eq $recordPerPage} class="active" {/if}>
                <a role="menuitem" href="{$url}&amp;items_per_page={$recordPerPage}&amp;page=1">{$recordPerPage}</a>
            </li>
        {/foreach}
    </ul>
</div>
