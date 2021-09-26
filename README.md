# web3.php

[![PHP](https://github.com/thtg88/web3.php/actions/workflows/php.yml/badge.svg)](https://github.com/thtg88/web3.php/actions/workflows/php.yml)
[![Licensed under the MIT License](https://img.shields.io/badge/License-MIT-blue.svg)](https://github.com/thtg88/web3.php/blob/master/LICENSE)

A PHP interface for interacting with the Ethereum blockchain and ecosystem.

Inspired by the great work of all previous [contributors](https://github.com/web3p/web3.php#contributors) on [web3p/web3.php](https://github.com/web3p/web3.php)

# Install

Add this to your composer.json

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/thtg88/web3.php"
        }
    ],
    "require": {
        "web3p/web3.php": "dev-master"
    }
```

Then execute the following from terminal

```
composer require web3p/web3.php
```

# Usage

### New instance
```php
use Web3\Web3;

$web3 = new Web3('http://localhost:8545');
```

### Using provider

```php
use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;

$web3 = new Web3(new HttpProvider(new HttpRequestManager('http://localhost:8545')));

// timeout
$web3 = new Web3(new HttpProvider(new HttpRequestManager('http://localhost:8545', 0.1)));
```

### You can use callback to each rpc call:

```php
[$err, $version] = $web3->clientVersion();

if ($err !== null) {
    // do something
    return;
}

echo 'Client version: ' . $version;
```

### Eth

```php
use Web3\Web3;

$web3 = new Web3('http://localhost:8545');
$eth = $web3->eth;
```

Or

```php
use Web3\Eth;

$eth = new Eth('http://localhost:8545');
```

### Net

```php
use Web3\Web3;

$web3 = new Web3('http://localhost:8545');
$net = $web3->net;
```

Or

```php
use Web3\Net;

$net = new Net('http://localhost:8545');
```

### Batch

web3

```php
[$errors, $data] = $web3->batch()
    ->clientVersion()
    ->hash('0x1234')
    ->execute();

if ($errors !== null) {
    // do something
    // it may throw exception or array of exception depends on error type
    // connection error: throw exception
    // json rpc error: array of exception
    return;
}

// do something
```

eth

```php
$eth->batch()
    ->protocolVersion()
    ->syncing()
    ->execute();

if ($errors !== null) {
    // do something
    return;
}

// do something
```

net

```php
$net->batch()
    ->version()
    ->listening()
    ->execute();

if ($errors !== null) {
    // do something
    return;
}

// do something
```

personal

```php
$personal->batch()
    ->listAccounts()
    ->newAccount('123456')
    ->execute();

if ($errors !== null) {
    // do something
    return;
}

// do something
```

### Contract

```php
use Web3\Contract;

$contract = new Contract('http://localhost:8545', $abi);

// deploy contract
$contract->bytecode($bytecode)->new($params, $callback);

// call contract function
$contract->at($contractAddress)->call($functionName, $params, $callback);

// change function state
$contract->at($contractAddress)->send($functionName, $params, $callback);

// estimate deploy contract gas
$contract->bytecode($bytecode)->estimateGas($params, $callback);

// estimate function gas
$contract->at($contractAddress)->estimateGas($functionName, $params, $callback);

// get constructor data
$constructorData = $contract->bytecode($bytecode)->getData($params);

// get function data
$functionData = $contract->at($contractAddress)->getData($functionName, $params);
```

# Assign value to outside scope(from callback scope to outside scope)

```php
$newAccount = '';

[$err, $account] = $web3->personal->newAccount('123456');

if ($err !== null) {
    echo 'Error: ' . $err->getMessage();
    return;
}

$newAccount = $account;
echo 'New account: ' . $account . PHP_EOL;
```

# Examples

To run examples, you need to run ethereum blockchain local (testrpc).

You can use [Ganache](https://www.trufflesuite.com/ganache) to set up a testchain and expose it on port 8545

# Develop

### Local php cli installed

1. Clone the repo and install packages.

```
git clone https://github.com/thtg88/web3.php.git && cd web3.php && composer install
```

2. Start Ganache workspace on port 8545

3. Change testHost in `TestCase.php`

```
/**
 * testHost
 *
 * @var string
 */
protected $testHost = 'http://127.0.0.1:8545';
```

4. Run test script

```
vendor/bin/phpunit
```

# API

Todo.

# License

MIT
