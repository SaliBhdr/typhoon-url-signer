<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 1/26/2019
 * Time: 11:24 PM
 */

namespace SaliBhdr\UrlSigner\Signature;

use SaliBhdr\UrlSigner\Exceptions\SignatureMissingException;
use SaliBhdr\UrlSigner\Exceptions\SignatureNotValidException;
use SaliBhdr\UrlSigner\Signers\SignerInterface;

class Signature implements SignatureInterface
{
    protected const SIGNATURE_KEY_NAME = 'sg';

    /**
     * Secret key used to generate signature.
     * @var string
     */
    protected $signer;

    /**
     * Create Signature instance.
     * @param SignerInterface $signer
     */
    public function __construct(SignerInterface $signer)
    {
        $this->signer = $signer;
    }

    /**
     * Generate an HTTP signature.
     * @param string $path The resource path.
     * @param array $params The manipulation parameters.
     * @return string The generated HTTP signature.
     */
    protected function generateSignature(String $path, array $params)
    {
        $path = $this->getSingableString($path, $params);

        return $this->signer->sign($path);
    }

    /**
     * get Singable string
     *
     * @param String $path
     * @param array $params
     * @return string
     */
    protected function getSingableString(String $path, array $params)
    {
        unset($params[static::SIGNATURE_KEY_NAME]);

        ksort($params);

        return ltrim($path, '/') . '?' . http_build_query($params);
    }

    /**
     * Add an HTTP signature to manipulation parameters.
     * @param  string $path The resource path.
     * @param  array $params The manipulation parameters.
     * @return array  The updated manipulation parameters.
     */
    public function addSignature($path, array $params)
    {
        return array_merge($params, [static::SIGNATURE_KEY_NAME => $this->generateSignature($path, $params)]);
    }

    /**
     * Validate a request signature.
     * @param  string $path The resource path.
     * @param  array $params The manipulation params.
     * @throws SignatureMissingException
     * @throws SignatureNotValidException
     */
    public function validate($path, array $params)
    {
        $hash = $params[static::SIGNATURE_KEY_NAME] ?? null;

        if (!isset($hash)) {
            throw new SignatureMissingException();
        }

        if (!$this->signer->verify($this->getSingableString($path,$params),$hash)) {
            throw new SignatureNotValidException();
        }
    }
}
