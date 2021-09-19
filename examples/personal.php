<?php

require('./exampleBase.php');

$personal = $web3->personal;
$newAccount = '';

echo 'Personal Create Account and Unlock Account' . PHP_EOL;

// create account
[$err, $account] = $personal->newAccount('123456');

if ($err !== null) {
    echo 'Error: ' . $err->getMessage();

    return;
}

$newAccount = $account;

echo 'New account: ' . $account . PHP_EOL;

[$err, $unlocked] = $personal->unlockAccount($newAccount, '123456');

if ($err !== null) {
    echo 'Error: ' . $err->getMessage();

    return;
}

if ($unlocked) {
    echo 'New account is unlocked!' . PHP_EOL;
} else {
    echo 'New account isn\'t unlocked' . PHP_EOL;
}

// get balance
[$err, $balance] = $web3->eth->getBalance($newAccount);

if ($err !== null) {
    echo 'Error: ' . $err->getMessage();

    return;
}

echo 'Balance: ' . $balance->toString() . PHP_EOL;

// remember to lock account after transaction
[$err, $locked] = $personal->lockAccount($newAccount);

if ($err !== null) {
    echo 'Error: ' . $err->getMessage();

    return;
}

if ($locked) {
    echo 'New account is locked!' . PHP_EOL;
} else {
    echo 'New account isn\'t locked' . PHP_EOL;
}
