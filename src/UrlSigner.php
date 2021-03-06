<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 10/24/2019
 * Time: 1:21 PM
 */

namespace SaliBhdr\UrlSigner;

use SaliBhdr\UrlSigner\Exceptions\UrlSignerException;
use SaliBhdr\UrlSigner\Signatures\SignatureInterface;

class UrlSigner implements UrlSignerInterface
{
    /** @var SignatureInterface $signature */
    protected $signature;

    public function __construct(SignatureInterface $signature)
    {
        $this->signature = $signature;
    }

    /**
     * @param string $url
     * @param array $params
     *
     * @return string
     */
    public function create(string $url, array $params = []) : string
    {
        if (empty($params)) {
            $this->parseUrl($url, $params);
        }

        return $url . '?' . http_build_query($this->signature->addSignature($url, $params));
    }

    /**
     * @param string $url
     * @param array $params
     *
     * @throws \SaliBhdr\UrlSigner\Exceptions\SignatureMissingException
     * @throws \SaliBhdr\UrlSigner\Exceptions\SignatureNotValidException
     * @throws \SaliBhdr\UrlSigner\Exceptions\SignatureTimestampMissingException
     * @throws \SaliBhdr\UrlSigner\Exceptions\SignatureUrlExpiredException
     */
    public function validate(string $url, array $params = []) : void
    {
        if (empty($params)) {
            $this->parseUrl($url, $params);
        }

        $this->signature->validate($url, $params);
    }

    /**
     * @param string $url
     * @param array $params
     *
     * @return bool
     */
    public function isValid(string $url, array $params = []) : bool
    {
        try {
            $this->validate($url, $params);
            $isValid = true;
        }
        catch (UrlSignerException $e) {
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * @param string $url
     * @param array $params
     */
    protected function parseUrl(string &$url, array &$params)
    {
        $parsedUrl = parse_url($url);

        parse_str($parsedUrl['query'] ?? '', $params);

        $this->clearUrl($url, $parsedUrl['query'] ?? '');
    }

    /**
     * clears query string from url
     *
     * @param string $url
     * @param string $query
     */
    protected function clearUrl(string &$url, string $query)
    {
        $url = str_replace('?' . $query, '', $url);
    }

}
