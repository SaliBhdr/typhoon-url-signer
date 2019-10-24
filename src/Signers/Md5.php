<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 10/24/2019
 * Time: 12:49 PM
 */

namespace SaliBhdr\UrlSigner\Signers;

class Md5 extends AbstractSigner
{
    /**
     * @param string $string
     * @return string
     */
    public function sign(string $string): string
    {
        return md5($this->signKey . ':' . $string);
    }


    /**
     * @param string $mustSign
     * @param string $signedBefore
     * @return bool
     */
    public function verify(string $mustSign, string $signedBefore): bool
    {
        return hash_equals($this->sign($mustSign), $signedBefore);
    }
}