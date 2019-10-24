<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 1/26/2019
 * Time: 11:24 PM
 */
namespace SaliBhdr\UrlSigner\Exceptions;

use Throwable;

class SignerNotFoundException extends UrlSignerException
{

    public function __construct(string $message = "The define signer is not found", int $code = 422, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
