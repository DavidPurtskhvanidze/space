{assign var="username" value=$user.username}
{assign var="currency_sign" value=$GLOBALS.custom_settings.transaction_currency}
{capture assign="item_name"}[[$item_name]]{/capture}
[[
Dear $username, <br /><br />
Please send us a payment in the amount of $currency_sign$amount for $item_name<br />

Your transaction reference number is $payment_id. <br /><br />

Thank you!]]
