<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 10/24/2019
 * Time: 5:25 PM
 */
return [
    'sign_key'  => env('URL_SIGN_KEY', ''),
    'signature' => 'SaliBhdr\UrlSigner\Signers\Signature',

    'signer' => 'md5', // Hmac || Md5 || Rsa

    'hmac' => [
        'algorithm' => 'sha256'
    ],

    'rsa' => [
        'algorithm'      => 'sha256',
        'signature_mode' => 2,
        'public_key'     => '',
        'private_key'    => ''
    ]
];