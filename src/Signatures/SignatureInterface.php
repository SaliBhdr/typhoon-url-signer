<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 1/26/2019
 * Time: 11:24 PM
 */
namespace SaliBhdr\UrlSigner\Signature;

interface SignatureInterface
{
    /**
     * Add an HTTP signature to manipulation params.
     * @param  string $path   The resource path.
     * @param  array  $params The manipulation params.
     * @return array  The updated manipulation params.
     */
    public function addSignature($path, array $params);

    /**
     * Validate a request signature.
     * @param  string             $path   The resource path.
     * @param  array              $params The manipulation params.
     * @throws \SaliBhdr\UrlSigner\Exceptions\SignatureMissingException
     * @throws \SaliBhdr\UrlSigner\Exceptions\SignatureNotValidException
     */
    public function validate($path, array $params);
}
