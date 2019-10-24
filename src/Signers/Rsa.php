<?php
/**
 * Created by PhpStorm.
 * User: b.momeni
 * Date: 11/16/2017
 * Time: 1:46 PM
 */

namespace SaliBhdr\UrlSigner\Signers;

use phpseclib\Crypt\RSA as BaseRSA;

class Rsa extends AbstractSigner
{
    /** @var BaseRSA $rsaSigner */
    protected $rsaSigner;

    protected $publicKey;

    protected $privateKey;
    /**
     * RsaSigner constructor.
     * @param int $signatureMode
     * @param string $signAlgorithm
     */
    public function __construct($signAlgorithm = 'sha256',int $signatureMode = BaseRSA::SIGNATURE_PKCS1)
    {
        parent::__construct('');

        $this->rsaSigner = new BaseRSA();
        $this->rsaSigner->setHash($signAlgorithm);
        $this->rsaSigner->setSignatureMode($signatureMode);
    }

    /**
     * @return BaseRSA
     */
    public function getRsaSigner() : BaseRSA
    {
        return $this->rsaSigner;
    }

    /**
     * @param $publicKey
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @param $private_key
     */
    public function setPrivateKey($private_key)
    {
        $this->privateKey = $private_key;
    }


    /**
     * @param string $string
     *
     * @return string
     */
    public function sign(string $string): string
    {
        $this->rsaSigner->loadKey($this->privateKey);

        return base64_encode($this->rsaSigner->sign($string));
    }

    /**
     * @param string $mustSign
     * @param string $signedBefore
     * @return bool
     */
    public function verify(string $mustSign, string $signedBefore): bool
    {
        $this->rsaSigner->loadKey($this->publicKey);

        return $this->rsaSigner->verify($mustSign, base64_decode($signedBefore));
    }
}