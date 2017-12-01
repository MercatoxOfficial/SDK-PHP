# Q.A.

## How to use Trade API SDK?

```php

$api = new MXTrade('MX:U00000:XXXXXXXXXXXXXXXXXXXXXXXXXXXX', 'MX:P00000:XXXXXXXXXXXXXXXXXXXXXXXXXXXX'); // Specify your public and private keys

$public = $api->pub(); // Set PUBLIC endpoint

/**
 * Just specify any API method as class member. Even if your SDK is not up to date it will work fine :)
 * E.g., we have added new API method cancellAll but you're not up-to-date. 
 * You need only to use $private->cancellAll(['symbol' => 'ETH_BTC'])
 *
 * Here is some examples of using SDK:
 */
 
$symbols = $public->symbols(); 
$book = $public->book('ETH_BTC');

$private = $api->priv(); // Set PRIVATE endpoint

$btc_balance = $private->getBalance(['symbol' => 'BTC']);
$new_order = $private->order([
  'symbol' => 'ETH_BTC',
  'amount' => 1.5,
  'price' => 0.05,
  'action' => 'buy',
  'type' => 'limit'
]);

```
