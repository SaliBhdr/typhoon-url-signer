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

    /**
     * RsaSigner constructor.
     * @param $signKey
     * @param int $signatureMode
     * @param string $signAlgorithm
     */
    public function __construct($signKey,$signAlgorithm = 'sha256',int $signatureMode = BaseRSA::SIGNATURE_PKCS1)
    {
        parent::__construct($signKey);

        $rsa = new BaseRSA();
        $rsa->setHash($signAlgorithm);
        $rsa->setSignatureMode($signatureMode);
        $rsa->loadKey($this->signKey);
    }


    public function sign(string $string): string
    {
        return $this->rsaSigner->sign($string);
    }

    /**
     * @param string $mustSign
     * @param string $signedBefore
     * @return bool
     */
    public function verify(string $mustSign, string $signedBefore): bool
    {
        return $this->rsaSigner->verify($this->rsaSigner->sign($mustSign), $signedBefore);
    }
}