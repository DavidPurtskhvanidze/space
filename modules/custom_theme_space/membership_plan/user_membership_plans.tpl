<br>
<div class="availabelMembershipPlans">
    <h2 class="title">[[Other Membership Plans]]</h2>
    {$contactFormPageUri = "{page_path id='contact'}"}
    <div class="alert alert-info" role="alert">[[To change your subscription before it expires please <a href="$contactFormPageUri">send a request</a> to the site administrator.]]</div>
    {foreach from=$membershipPlans item=membershipPlan}
        <h3>
            [[$membershipPlan.name]]
        </h3>
        <div class="well">
            <table class="table">
                <tbody class="table-hover">
                <tr class="info">
                    <th>[[Membership Plan]]</th>
                    <td>[[$membershipPlan.name]]</td>
                </tr>
                <tr>
                    <th>[[Price]]</th>
                    <td>{$membershipPlan.price}</td>
                </tr>
                <tr>
                    <th>[[Type]]</th>
                    <td>[[{$membershipPlan.type}]]</td>
                </tr>
                <tr>
                    <th>[[Duration(days)]]</th>
                    <td>[[{$membershipPlan.subscription_period}]]</td>
                </tr>
                <tr>
                    <th>[[Max Number of Listings]]</th>
                    <td>{$membershipPlan.classifieds_listing_amount}</td>
                </tr>
                <tr>
                    <th>[[Description]]</th>
                    <td>[[$membershipPlan.description]]</td>
                </tr>
                </tbody>
            </table>
            {include file="packages.tpl" packages=$membershipPlan.packages}
        </div>
        <br>
    {/foreach}
</div>
