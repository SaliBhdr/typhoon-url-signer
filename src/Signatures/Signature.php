<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 1/26/2019
 * Time: 11:24 PM
 */

namespace SaliBhdr\UrlSigner\Signatures;

use SaliBhdr\UrlSigner\Exceptions\SignatureMissingException;
use SaliBhdr\UrlSigner\Exceptions\SignatureNotValidException;
use SaliBhdr\UrlSigner\Exceptions\SignatureTimestampMissingException;
use SaliBhdr\UrlSigner\Exceptions\SignatureUrlExpiredException;
use SaliBhdr\UrlSigner\Signers\SignerInterface;

class Signature implements SignatureInterface
{
    public const SIGNATURE_KEY_NAME = 'sg';
    public const SIGNATURE_TIMESTAMP_KEY = 'ts';

    /**
     * Secret key used to generate signature.
     * @var string
     */
    protected $signer;

    /**
     * time to live in seconds
     * @var int
     */
    protected $ttl;

    /**
     * SignatureInterface constructor.
     *
     * @param SignerInterface $signer
     * @param int|null $ttl
     */
    public function __construct(SignerInterface $signer, ?int $ttl = null)
    {
        $this->signer = $signer;
        $this->ttl = $ttl;
    }

    /**
     * Generate an HTTP signature.
     *
     * @param string $url The resource path.
     * @param array $params The manipulation parameters.
     *
     * @return string The generated HTTP signature.
     */
    protected function generateSignature(String $url, array $params)
    {
        $url = $this->getSingableString($url, $params);

        return $this->signer->sign($url);
    }

    /**
     * get Singable string
     *
     * @param String $url
     * @param array $params
     *
     * @return string
     */
    protected function getSingableString(String $url, array $params)
    {
        unset($params[static::SIGNATURE_KEY_NAME]);

        ksort($params);

        return ltrim($url, '/') . '?' . http_build_query($params);
    }

    /**
     * Add an HTTP signature to manipulation parameters.
     *
     * @param  string $url The resource path.
     * @param  array $params The manipulation parameters.
     *
     * @return array  The updated manipulation parameters.
     */
    public function addSignature($url, array $params)
    {
        if (!is_null($this->ttl))
            $params[static::SIGNATURE_TIMESTAMP_KEY] = time() + $this->ttl;

        return array_merge($params, [static::SIGNATURE_KEY_NAME => $this->generateSignature($url, $params)]);
    }

    /**
     * Validate a request signature.
     *
     * @param  string $url The resource path.
     * @param  array $params The manipulation params.
     *
     * @throws SignatureMissingException
     * @throws SignatureNotValidException
     * @throws SignatureTimestampMissingException
     * @throws SignatureUrlExpiredException
     */
    public function validate($url, array $params)
    {
        $hash = $params[static::SIGNATURE_KEY_NAME] ?? null;

        if (!isset($hash))
            throw new SignatureMissingException();


        if (!$this->signer->verify($this->getSingableString($url, $params), $hash))
            throw new SignatureNotValidException();


        if(isset($this->ttl)){
            $timestamp = $params[static::SIGNATURE_TIMESTAMP_KEY] ?? null;

            if (!isset($timestamp))
                throw new SignatureTimestampMissingException();


            if (time() > $timestamp)
                throw new SignatureUrlExpiredException();
        }

    }
}
