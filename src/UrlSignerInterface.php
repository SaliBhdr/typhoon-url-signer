<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 10/24/2019
 * Time: 1:56 PM
 */

namespace SaliBhdr\UrlSigner\Signers;


interface UrlSignerInterface
{
    /**
     * @param string $url
     * @param array $params
     * @return string
     */
    public function makeUrl(string $url, array $params): string;

    /**
     * @param string $url
     * @param array $params
     * @throws \SaliBhdr\UrlSigner\Exceptions\SignatureMissingException
     * @throws \SaliBhdr\UrlSigner\Exceptions\SignatureNotValidException
     */
    public function validateUrl(string $url, array $params): void;

    /**
     * @param string $url
     * @param array $params
     * @return bool
     */
    public function isValidUrl(string $url, array $params): bool;
}