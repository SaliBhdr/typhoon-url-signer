<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 10/24/2019
 * Time: 1:51 PM
 */

namespace SaliBhdr\UrlSigner;

use SaliBhdr\UrlSigner\Signatures\Signature;
use SaliBhdr\UrlSigner\Signers\Md5;

class Md5UrlSigner implements UrlSignerInterface
{
    /** @var UrlSigner $urlSigner */
    protected $urlSigner;

    public function __construct(string $signKey,$ttl = 7200)
    {
        $signer    = new Md5($signKey);
        $signature = new Signature($signer,$ttl);

        $this->urlSigner = new UrlSigner($signature);
    }

    /**
     * @param string $url
     * @param array $params
     * @return string
     */
    public function create(string $url, array $params = []): string
    {
        return $this->urlSigner->create($url, $params);
    }

    /**
     * @param string $url
     * @param array $params
     *
     * @throws Exceptions\SignatureMissingException
     * @throws Exceptions\SignatureNotValidException
     * @throws Exceptions\SignatureTimestampMissingException
     * @throws Exceptions\SignatureUrlExpiredException
     */
    public function validate(string $url, array $params = []): void
    {
        $this->urlSigner->validate($url, $params);
    }

    /**
     * @param string $url
     * @param array $params
     * @return bool
     */
    public function isValid(string $url, array $params = []): bool
    {
        return $this->urlSigner->isValid($url, $params);
    }

}
