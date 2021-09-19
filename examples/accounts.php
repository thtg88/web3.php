<?php

require('./exampleBase.php');

$eth = $web3->eth;

echo 'Eth Get Account and Balance' . PHP_EOL;

[$err, $accounts] = $eth->accounts();

if ($err !== null) {
    echo 'Error: ' . $err->getMessage();

    return;
}
foreach ($accounts as $account) {
    echo 'Account: ' . $account . PHP_EOL;

    [$err, $balance] = $eth->getBalance($account);

    if ($err !== null) {
        echo 'Error: ' . $err->getMessage();

        return;
    }

    echo 'Balance: ' . $balance . PHP_EOL;
}
