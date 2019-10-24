<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 10/24/2019
 * Time: 5:57 PM
 */
namespace SaliBhdr\UrlSigner\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class UrlSigner
 * @package SaliBhdr\UrlSigner\Laravel\Facades
 * @method string makeUrl(string $url, array $params = [])
 * @method void validateUrl(string $url, array $params = [])
 * @method bool isValidUrl(string $url, array $params = [])
 */
class UrlSigner extends Facade
{
    /**    * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'urlSigner';
    }
}