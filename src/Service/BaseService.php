<?php

namespace App\Service;

use App\Exception\DataAbsentException;
use GuzzleHttp\Client;
use Codeception\Util\HttpCode;
use App\Traits\GeneralFunctionality;
use App\Service\Abstracts\AbstractService;
use App\Interfaces\Service\ServiceInterface;
use App\Interfaces\Manager\ManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class BaseService
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
     * A content data of a certain call
     *
     * @var array
     */
    public $content;

    /**
     * @var Client
     */
    protected $client;

    /**
     * An url's for making a call at
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
     * If the value equals null it is a creation entity mode
     * in the Commerce Store
     *
     * @var string
     */
    protected $additionalURLPart = '/';

    /**
     * A case when we need only a response code
     *
     * @var int
     */
    protected $getResponse = 0b0;

    /**
     * A default request type's
     *
     * @var string
     */
    protected $requestType = self::REQUEST_TYPE_POST;

    /**
     * A bit for mapping issue's
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
        $this->dispatcher = new EventDispatcher();
        $this->client = new Client([
            'timeout'  => 60.0,
        ]);

        // Default parameters are
        // just skip this one for now
        $this->url = '';
        $this->urlSymfony = '';
        $this->headers = [];
    }

    public function resetParametersBug()
    {
        foreach (self::PARAMETERS_BAG as $parameter) {
            $this->{$parameter} = [];
        }
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
     * Set up fields for fieldsMatches in a request to the Commerce Store
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
            $body->{$key} = $this->{'get' . ucfirst($key)}();
        }

        return $body;
    }


      /**
     * Make an asynchronous request
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
            throw new HttpException($exception->getCode(), preg_replace('/[htps]+:\/\/.*?\//', '', $exception->getMessage()));
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
}
