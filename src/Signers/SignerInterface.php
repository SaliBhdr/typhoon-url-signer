<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 10/24/2019
 * Time: 12:48 PM
 */

namespace SaliBhdr\UrlSigner\Signers;

interface SignerInterface
{

    /**
     * @param $string
     * @return string
     */
    public function sign(string $string) : string;

    /**
     * @param string $mustSign
     * @param string $signedBefore
     * @return bool
     */
    public function verify(string $mustSign,string $signedBefore) : bool;
}