<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 10/24/2019
 * Time: 5:30 PM
 */

namespace SaliBhdr\UrlSigner\Laravel;


use SaliBhdr\UrlSigner\Exceptions\SignerNotFoundException;
use SaliBhdr\UrlSigner\Signers\Hmac;
use SaliBhdr\UrlSigner\Signers\Md5;
use SaliBhdr\UrlSigner\Signers\Rsa;
use SaliBhdr\UrlSigner\UrlSigner;
use SaliBhdr\UrlSigner\UrlSignerInterface;

class LaravelUrlSigner
{
    /** @var array */
    protected $config;

    /** @var UrlSignerInterface $urlSigner */
    protected $urlSigner;

    /** @var string */
    protected $urlSignerClassName;

    /**
     * UrlSigner constructor.
     * @throws SignerNotFoundException
     */
    public function __construct()
    {
        $this->config = config('urlSigner');

        $this->setUrlSigner();
    }

    /**
     * @return string
     */
    public function getSignerClassName() : string
    {
        return UrlSigner::class;
    }

    /**
     * @return string
     */
    private function getSignKey()
    {
        return $this->config['sign_key'];
    }

    /**
     * @param $signer
     *
     * @return mixed
     */
    private function getAlgorithm(string $signer) : string
    {
        return $this->config[$signer]['algorithm'];
    }

    /**
     * @return UrlSignerInterface
     */
    public function getUrlSigner() : UrlSignerInterface
    {
        return $this->urlSigner;
    }

    /**
     * @throws SignerNotFoundException
     */
    protected function setUrlSigner() : void
    {
        $signer = $this->config['signer'];

        switch ($signer) {
            case 'md5':
                $signerInstance = new Md5($this->getSignKey());
                break;
            case 'hmac':
                $signerInstance = new Hmac($this->getSignKey(), $this->getAlgorithm($signer));
                break;
            case 'rsa' :
                $signerInstance = new Rsa($this->getSignKey(), $this->getAlgorithm($signer));
                break;
            default:
                throw new SignerNotFoundException();
        }

        $signature = new $this->config['signature']($signerInstance);

        $this->urlSigner = new UrlSigner($signature);
    }

}