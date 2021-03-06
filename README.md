# Typhoon Url Signer

![Salibhdr|typhoon](https://drive.google.com/a/domain.com/thumbnail?id=12yntFCiYIGJzI9FMUaF9cRtXKb0rXh9X)

[![Total Downloads](https://poser.pugx.org/SaliBhdr/typhoon-url-signer/downloads)](https://packagist.org/packages/SaliBhdr/typhoon-url-signer)
[![Latest Stable Version](https://poser.pugx.org/SaliBhdr/typhoon-url-signer/v/stable)](https://packagist.org/packages/SaliBhdr/typhoon-url-signer)
[![Latest Unstable Version](https://poser.pugx.org/SaliBhdr/typhoon-url-signer/v/unstable)](https://packagist.org/packages/SaliBhdr/typhoon-url-signer)
[![License](https://poser.pugx.org/SaliBhdr/typhoon-url-signer/license)](https://packagist.org/packages/SaliBhdr/typhoon-url-signer)

## Introduction

Typhoon Url Signer is a package that signs and validates URLs with ease.
You can make secure URLs for your files and any kind of URLs that you want so that no one can access them without permission.
You can make URLs with a limited lifetime to make them expire.

You can use this package both standalone and with your Laravel application

**Features**
- Create secure URLs with expire time
- Create secure URLs without expire time
- Validate URLs 
- Use both with Laravel and standalone
- Add your signers with your logic (md5, Hmac, etc.) (standalone mode)
- Add your URL signer with your logic (standalone mode)
- Add your signature (both Laravel and standalone)

## Installation

#### Install with Composer
```sh
 $ composer require salibhdr/typhoon-url-signer
```
## Getting started

### Standalone

You are ready to use the package and no other configuration needed.

### Laravel and lumen

##### Laravel

Register the `UrlSignerServiceProvider`
in your config/app.php configuration file:

---

```php
'providers' => [

     // Other service providers...
     
     SaliBhdr\UrlSigner\Laravel\ServiceProviders\UrlSignerServiceProvider::class,
],
```
Run `vendor:publish` command:

```sh
php artisan vendor:publish --provider="SaliBhdr\UrlSigner\Laravel\ServiceProviders\UrlSignerServiceProvider"
```
It will generate the `urlSigner.php` under config directory.

Copy `URL_SIGN_KEY` to your env:

```sh

URL_SIGN_KEY=

```
Run the `urlSigner:generate` command to generate a signKey:

```sh
php artisan urlSigner:generate
```
It will generate the a sign key in `.env` file.

##### Lumen

----

Register The the `UrlSignerServiceProvider` In bootstrap/app.php:

```php
$app->register(SaliBhdr\UrlSigner\Laravel\ServiceProviders\UrlSignerServiceProvider::class);
```

Copy the package config file to config directory (you may need to create one):

Copy `URL_SIGN_KEY` to your env:

```sh

URL_SIGN_KEY=

```
Run the `urlSigner:generate` command to generate a signKey:

```sh
php artisan urlSigner:generate

```
It will generate the a sign key in `.env` file.

## Usage

### General Description

You have 3 options to sign urls:

1) With Md5 signer
2) With Hmac signer
3) With base signer

All of 3 signers above has implemented form `SaliBhdr\UrlSigner\UrlSignerInterface`
and has 3 methods:

1) create($url,$params) : makes signed url base on input
2) validate($url,$params) : validates signed url throws exception base on input
3) isValid($url,$params) : validates and return true/false instead of exception

All 3 methods sign method take 2 parameters as input.The $url parameter and $params.
you can pass only url with query string attach to it:

```php
<?php
$url = 'www.example.com/api/v1/book?timestamp=153664546&id=2';


$signedUrl = $urlSigner->create($url);

```
Or you can pass url and query separately :
```php
<?php
$url = 'www.example.com/api/v1/book';

$params = [
    'timestamp' => '153664546',
    'id' => 2
];

$signedUrl = $urlSigner->create($url,$params);

```

So keep this in mind in all 3 methods.

Feel free to make your own signer by implementing `UrlSignerInterface`.

The url signer default ttl is 7200 seconds (2 hours). 
Pass null to ttl so that
the url's will not expire at all.

### Standalone

#### With Md5 signer

Make instance of `Md5UrlSigner`:

```php
<?php

use SaliBhdr\UrlSigner\Md5UrlSigner;

//your sign key
$signKey = 'EKtF4lFP6D1FjBGtSRIk1gGn2YCRmtGPocBWV39wAeM=';
// default ttl is 7200 seconds
// pass null to make url's without expire time
$ttl = 7200; 

$urlSigner = new Md5UrlSigner($signKey,$ttl);

```

#### With HmacUrlSigner signer

Make instance of `HmacUrlSigner`:

```php
<?php

use SaliBhdr\UrlSigner\HmacUrlSigner;

//your sign key
$signKey = 'EKtF4lFP6D1FjBGtSRIk1gGn2YCRmtGPocBWV39wAeM=';
$algorithm = 'sha1';
// default ttl is 7200 seconds
// pass null to make url's without expire time
$ttl = 7200; 

$urlSigner = new HmacUrlSigner($signKey,$algorithm,$ttl);

```
The HmacUrlSigner gets algorithm through second parameter. 
Default hashing algorithm is `sha256`. Pass second
parameter if you want to pass another algorithm other than `sha256`.
You can see list of all available algorithms [here][algorithms]

#### With base UrlSigner signer

The url signer ecosystem is working based on 3 main class:

1) the signer : is the hash method class
2) the signature : is the main class that signs the url based on the signer
3) the urlSigner : is the class that uses the signature to make and validate urls

So by the description above you must define all 3 to make the base url signer work.
This way you are free to use any signer and signature to make urls as long
as implement `SignerInterface` for the signer and `SignatureInterface` for the
signature.

First make a **signer** 

You can use one of 3 signers built in this package.
1) SaliBhdr\UrlSigner\Signers\Md5
2) SaliBhdr\UrlSigner\Signers\Hmac
3) SaliBhdr\UrlSigner\Signers\Rsa
```php
<?php

use SaliBhdr\UrlSigner\Signers\Md5;
use SaliBhdr\UrlSigner\Signers\Hmac;
use SaliBhdr\UrlSigner\Signers\Rsa;
use phpseclib\Crypt\RSA as BaseRSA;

//-------------Md5 signer example-------------
//your sign key
$signKey = 'EKtF4lFP6D1FjBGtSRIk1gGn2YCRmtGPocBWV39wAeM=';

$signer = new Md5($signKey);

//-------------Hmac signer example------------

$signer = new Hmac($signKey);

//-------------Rsa signer example-------------

/* Rsa needs 2 extra parameters
 * a public_key and a private_key
 * It will not work if you don't provide these two
 */
$algorithm = 'sha1'; // default is sha256
$signMode = BaseRSA::SIGNATURE_PKCS1;

$signer = new Rsa($algorithm,$signMode);

$signer->setPublicKey('----RSA PUBLIC KEY HERE----');
$signer->setPrivateKey('----RSA PRIVATE KEY HERE----');

```

Second make a **signature** and path the signer:

```php
<?php

use SaliBhdr\UrlSigner\Signatures\Signature;

// default ttl is 7200 seconds
// pass null to make url's without expire time
$ttl = 7200; 

$signature = new Signature($signer,$ttl);

```
**Third and final step make UrlSigner** and path the signature:
```php
<?php

use SaliBhdr\UrlSigner\UrlSigner;

$urlSigner = new UrlSigner($signature);

```

---

**Now you can use the url signer:**

Creating signed url:

```php
<?php

$url = 'www.example.com/api/v1/book';

$params = [
    'timestamp' => '153664546',
    'id' => 2
];

$signedUrl = $urlSigner->create($url,$params);

```

Validate signed url:
```php
<?php

// throws exception
$urlSigner->validate($signedUrl);

// returns true/false
echo $urlSigner->isValid($signedUrl) ? 'valid' : 'notValid';

```
The validate() method will throw one these 2 errors:
1) SignatureMissingException : If the url has no `sg` parameter in it
2) SignatureNotValidException : If the `sg` parameter is not a valid one
3) SignatureTimestampMissingException : If the url has no `ts` parameter in it
4) SignatureUrlExpiredException : If the link is expired

**Note 1:** If you want to handle exceptions, All exceptions are extended from `UrlSignerException` 
**Note 2:** The Url expiration and missing timestamp exception are throw when you define a ttl (time to live)

### Laravel

---

**Notice:** Please read Standalone section above for read the details about methods.

The url signer default ttl is 7200 seconds (2 hours). 
Set null to ttl in config so that
the url's will not expire at all.

You can use `UrlSigner` facade to sign and validate urls.

```php
<?php

use UrlSigner;

$url = 'www.example.com/api/v1/book?timestamp=153664546&id=2';

$signedUrl = UrlSigner::create($url);

```
Or you can pass url and query separately :

```php
<?php
use SaliBhdr\UrlSigner\Laravel\Facades\UrlSigner;

$url = 'www.example.com/api/v1/book';

$params = [
    'timestamp' => '153664546',
    'id' => 2
];

$signedUrl = UrlSigner::create($url, $params);

```

To validate url's :

```php
<?php

//throws exception
UrlSigner::validate($signedUrl);

// returns true/false
echo UrlSigner::isValid($signedUrl) ? 'valid':'notValid';

```

## Todos

 - Write Tests
 
Issues
----
You can report issues in github repository [here][lk1] 

License
----
Typhoon-Url-Signer is released under the MIT License.

Built with ❤ for you.

**Free Software, Hell Yeah!**

Contributing
----
Contributions, useful comments, and feedback are most welcome!


   [lk1]: <https://github.com/SaliBhdr/typhoon-url-signer/issues>
   [algorithms]: <https://www.php.net/manual/en/function.hash-algos.php#example-932>
