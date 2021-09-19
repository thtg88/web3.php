<?php

require('./exampleBase.php');

$eth = $web3->eth;

echo 'Eth Send Transaction' . PHP_EOL;

[$err, $accounts] = $eth->accounts();

if ($err !== null) {
    echo 'Error: ' . $err->getMessage();

    return;
}

$fromAccount = $accounts[0];

$toAccount = $accounts[1];

// get balance
[$err, $balance] = $eth->getBalance($fromAccount);

if ($err !== null) {
    echo 'Error: ' . $err->getMessage();

    return;
}

echo $fromAccount . ' Balance: ' . $balance . PHP_EOL;

[$err, $balance] = $eth->getBalance($toAccount);

if ($err !== null) {
    echo 'Error: ' . $err->getMessage();

    return;
}

echo $toAccount . ' Balance: ' . $balance . PHP_EOL;

// send transaction
[$err, $transaction] = $eth->sendTransaction([
    'from' => $fromAccount,
    'to' => $toAccount,
    'value' => '0x11',
]);

if ($err !== null) {
    echo 'Error: ' . $err->getMessage();

    return;
}

echo 'Tx hash: ' . $transaction . PHP_EOL;

// get balance
[$err, $balance] = $eth->getBalance($fromAccount);

if ($err !== null) {
    echo 'Error: ' . $err->getMessage();

    return;
}

echo $fromAccount . ' Balance: ' . $balance . PHP_EOL;

[$err, $balance] = $eth->getBalance($toAccount);

if ($err !== null) {
    echo 'Error: ' . $err->getMessage();

    return;
}

echo $toAccount . ' Balance: ' . $balance . PHP_EOL;
