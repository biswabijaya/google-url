<?php
/**
 * This file is part of the badams\GoogleUrl library
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/badams/google-url
 * @package badams/google-url
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace badams\GoogleUrl\Actions;

use badams\GoogleUrl\Exceptions\GoogleUrlException;
use badams\GoogleUrl\Resources\Analytics;
use badams\GoogleUrl\Resources\Url;
use badams\GoogleUrl\ActionInterface;
use GuzzleHttp\Message\ResponseInterface;

/**
 * Class Shorten
 * @package badams\GoogleUrl\Actions
 * @link https://developers.google.com/url-shortener/v1/url/insert
 */
class Expand implements ActionInterface
{
    /**
     * @var string
     */
    protected $shortUrl;

    /**
     * @var
     */
    protected $projection;

    /**
     * Shorten constructor.
     * @param $shortUrl
     * @throws GoogleUrlException
     * @internal param $longUrl
     */
    public function __construct($shortUrl, $projection = null)
    {
        if (empty($shortUrl)) {
            throw new GoogleUrlException('No URL provided');
        }

        if ($projection && !in_array($projection, [Analytics::FULL, Analytics::CLICKS, Analytics::TOP])) {
            throw new GoogleUrlException('Invalid Projection Parameter');
        }

        $this->shortUrl = $shortUrl;
        $this->projection = $projection;
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return 'GET';
    }

    /**
     * @return array
     */
    public function getRequestOptions()
    {
        return [
            'query' => array_filter([
                'shortUrl' => $this->shortUrl,
                'projection' => $this->projection
            ])
        ];
    }

    /**
     * @param ResponseInterface $response
     * @return Url
     */
    public function processResponse(ResponseInterface $response)
    {
        $obj = json_decode($response->getBody()->getContents());
        return Url::createFromJson($obj);
    }
}
