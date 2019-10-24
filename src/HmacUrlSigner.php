<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 10/24/2019
 * Time: 1:51 PM
 */

namespace SaliBhdr\UrlSigner;

use SaliBhdr\UrlSigner\Signature\Signature;

class HmacUrlSigner implements UrlSignerInterface
{

    /** @var UrlSigner $urlSigner */
    protected $urlSigner;

    public function __construct(string $signKey,string $algorithm = 'sha256')
    {
        $signer    = new Hmac($signKey,$algorithm);

        $signature = new Signature($signer);

        $this->urlSigner = new UrlSigner($signature);
    }

    /**
     * @param string $url
     * @param array $params
     * @return string
     */
    public function makeUrl(string $url, array $params = []): string
    {
        return $this->urlSigner->makeUrl($url, $params);
    }

    /**
     * @param string $url
     * @param array $params
     * @throws \SaliBhdr\UrlSigner\Exceptions\SignatureMissingException
     * @throws \SaliBhdr\UrlSigner\Exceptions\SignatureNotValidException
     */
    public function validateUrl(string $url, array $params = []): void
    {
        $this->urlSigner->validateUrl($url, $params);
    }

    /**
     * @param string $url
     * @param array $params
     * @return bool
     */
    public function isValidUrl(string $url, array $params = []): bool
    {
        return $this->urlSigner->isValidUrl($url, $params);
    }

}