<?php
/**
 * Created by PhpStorm.
 * User: b.momeni
 * Date: 9/7/2017
 * Time: 11:21 AM
 */

namespace SaliBhdr\UrlSigner\Signers;

class Hmac extends AbstractSigner
{

    protected $signAlgorithm;

    public function __construct(string $signKey,$signAlgorithm = 'sha256')
    {
        $this->signAlgorithm = $signAlgorithm;

        parent::__construct($signKey);
    }

    /**
     * @param string $string
     * @return string
     */
    public function sign(string $string) : string
    {
        return hash_hmac($this->signAlgorithm, $string, $this->signKey, false);
    }

    /**
     * @param string $mustSign
     * @param string $signedBefore
     * @return bool
     */
    public function verify(string $mustSign,string $signedBefore): bool
    {
        return strcmp($this->sign($mustSign),$signedBefore) == 0;
    }
}