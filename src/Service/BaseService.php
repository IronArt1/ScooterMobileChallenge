<?php

namespace App\Service;

use GuzzleHttp\Client;
use App\Traits\GeneralFunctionality;
use App\Service\Abstracts\AbstractService;
use App\Interfaces\Service\ServiceInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class BaseService
 *
 * @package App\Service
 */
class BaseService extends AbstractService
{
    /**
     * A trait's for general use purposes
     */
    use GeneralFunctionality;

    /**
     * Types of tokens'.
     */
    public const TOKEN_TYPES_HOLDER = [
        'scooterToken',
        'mobileToken'
    ];

    /**
     * @var Client
     */
    protected $client;

    /**
     * An url's for making a call
     *
     * @var string
     */
    protected $url;

    /**
     * Headers for a call're
     *
     * @var []
     */
    protected $headers;

    /**
     * A stack of body values
     *
     * @var array
     */
    public $stack = [];

    /**
     * @var array
     */
    public $types;

    /**
     * An url's for making a call at
     *
     * @var string
     */
    protected $urlSymfony;

    /**
     * @var object EventDispatcher
     */
    private $dispatcher;

    /**
     * URI + query string are
     *
     * @var string
     */
    protected $additionalURLPart = '/';

    /**
     * A case when we need only a response code + JSON
     *
     * @var int
     */
    protected $getResponse = 0b1;

    /**
     * A default request type's
     *
     * @var string
     */
    protected $requestType = self::REQUEST_TYPE_PATCH;

    /**
     * A bit for a mapping issue's
     *
     * @var int
     */
    protected $addResult = 0x0;

    /**
     * BaseService constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        // usually there is a need for this one, so will see...
        $this->dispatcher = new EventDispatcher();
        $this->client = new Client([
            'timeout'  => 60.0,
        ]);

        $this->url = '';
        // right now there is only need in `symfonyBackendURL`
        $this->urlSymfony = $data[0];
        $this->headers = self::HEADERS;
    }

    /**
     * Setting up body fields.
     *
     * @param array $data
     *
     * @return ServiceInterface
     */
    public function setBodyFields(array $data): ServiceInterface
    {
        foreach ($data as $key => $parameters) {
            $this->{$key} = $parameters;
            $this->stack[] = $key;
        }

        return $this;
    }

    /**
     * Set up fields for fieldsMatches in a request
     *
     * @param array $data
     * @param array $parameters
     * @param string|null $defaultName
     * @param string|null $defaultProperty
     *
     * @return array
     */
    public function setUpFieldsData(
        array &$data,
        array $parameters = [],
        string $defaultName = null,
        string $defaultProperty = null
    ): array
    {
        foreach($parameters as $key => $entry) {
            // parameters will be here latter
        }

        return $data;
    }

    /**
     * Make data for body
     *
     * @return array
     */
    public function makeBody(): \stdClass
    {
        $body = new \stdClass();
        foreach ($this->stack as $key) {
            $body->{$key} = $this->{'get' . ucfirst($key)}($key);
        }

        return $body;
    }

      /**
     * Makes an asynchronous request
     *
     * @param mixed $body
     * @param string $urlType
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function requestAsync($body, string $urlType)
    {
        try {
            $response = $this->client->request(
                $this->requestType,
                $this->{$urlType} . $this->additionalURLPart ?? '',
                [
                    'headers' => $this->headers,
                    'body' => json_encode($body),
                ]
            );
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            throw new HttpException($exception->getCode(), $exception->getMessage());
        } catch (\Exception $exception) {
            throw new HttpException($exception->getCode(), $exception->getMessage());
        }

        $responseContent = json_decode($response->getBody()->getContents(), true);

        return ($this->getResponse) ?
            [
                'responseCode' => $response->getStatusCode(),
                'responseData' => $responseContent
            ] :
            (($this->addResult) ? ['result' => [$responseContent]] : $responseContent);
    }

    /**
     * Set up query parameters
     *
     * @param $parameters
     * @return $this
     */
    protected function setUpQueryParameters($parameters): self
    {
        $i = 0;
        $count = count($parameters);
        $this->additionalURLPart .= '?';
        foreach ($parameters as $parameter => $value)  {
            $i++;
            $this->additionalURLPart .= "$parameter=$value" . (($i < $count) ? '&' : '');
        }

        return $this;
    }

    /**
     * Extract required data from content response data
     *
     * @param $baseLabel
     *
     * @param null $methodLabel
     *
     * @return mixed|null
     */
    protected function getContentResponseData($baseLabel, $methodLabel = null){
        $result = null;

        // skipping for now

        return $result;
    }

    /**
     * Adds URI
     *
     * @param $URI
     * @return $this
     */
    public function addResouceIdentifier($URI)
    {
        $this->additionalURLPart = $URI;

        return $this;
    }

    /**
     * Sets up an authorization header
     *
     * @param string $token
     * @return $this
     */
    public function setAuthHeader($token)
    {
        $this->headers['Authorization'] = self::HEADERS['Authorization'] . $token;

        return $this;
    }

    /**
     * Gets parameters that were set dynamically during the run-time cycle
     * The reason why we are doing so is that sometimes we need to override
     * those methods in Services because there are some weird setting need
     * to be done for a `body` parameter
     *
     * @param $arg
     * @return mixed|null
     */
    public function __call($method, $args)
    {
        // there is only one parameter is called, so testing purposes that's enough
        return (property_exists($this, $args[0]) && isset($this->{$args[0]})) ? $this->{$args[0]} : null;
    }
}
