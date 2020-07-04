<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 1/26/2019
 * Time: 11:24 PM
 */
namespace SaliBhdr\UrlSigner\Signatures;

use SaliBhdr\UrlSigner\Signers\SignerInterface;

interface SignatureInterface
{
    /**
     * SignatureInterface constructor.
     *
     * @param SignerInterface $signer
     * @param int|null $ttl
     */
    public function __construct(SignerInterface $signer, ?int $ttl = null);

    /**
     * Add an HTTP signature to manipulation params.
     * @param  string $url   The resource path.
     * @param  array  $params The manipulation params.
     * @return array  The updated manipulation params.
     */
    public function addSignature($url, array $params);

    /**
     * Validate a request signature.
     * @param  string             $url   The resource path.
     * @param  array              $params The manipulation params.
     * @throws \SaliBhdr\UrlSigner\Exceptions\SignatureMissingException
     * @throws \SaliBhdr\UrlSigner\Exceptions\SignatureNotValidException
     * @throws \SaliBhdr\UrlSigner\Exceptions\SignatureTimestampMissingException
     * @throws \SaliBhdr\UrlSigner\Exceptions\SignatureUrlExpiredException
     */
    public function validate($url, array $params);
}
