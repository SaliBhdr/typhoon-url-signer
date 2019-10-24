<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 10/24/2019
 * Time: 12:57 PM
 */

namespace SaliBhdr\UrlSigner\Signers;


abstract class AbstractSigner implements SignerInterface
{
    protected $signKey;

    /**
     * SignerInterface constructor.
     * @param $signKey
     */
    public function __construct(string $signKey)
    {
        $this->signKey = $signKey;
    }
}