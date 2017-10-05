<?php

namespace Reliv\PipeRat2\ResponseFormat\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetDataModel;
use Reliv\PipeRat2\Core\Api\GetOptions;
use Reliv\PipeRat2\ResponseFormat\Api\IsResponseFormattable;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ResponseFormatXml extends ResponseFormatAbstract
{
    /**
     * @return string
     */
    public static function configKey(): string
    {
        return 'response-format-xml';
    }

    /**
     * @var array
     */
    protected $defaultAcceptTypes
        = [
            'application/xml'
        ];

    /**
     * @var IsResponseFormattable
     */
    protected $isResponseFormattable;

    /**
     * @var GetDataModel
     */
    protected $getDataModel;

    /**
     * @param GetOptions            $getOptions
     * @param IsResponseFormattable $isResponseFormattable
     * @param GetDataModel          $getDataModel
     */
    public function __construct(
        GetOptions $getOptions,
        IsResponseFormattable $isResponseFormattable,
        GetDataModel $getDataModel
    ) {
        $this->isResponseFormattable = $isResponseFormattable;
        $this->getDataModel = $getDataModel;
        parent::__construct($getOptions);
    }

    /**
     * arrayToXml
     *
     * @param array             $data
     * @param \SimpleXMLElement $xml_data
     *
     * @return void
     */
    protected function arrayToXml(array $data, \SimpleXMLElement &$xml_data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                //dealing with <0/>..<n/> issues
                if (is_numeric($key)) {
                    $key = 'item' . $key;
                }
                $subNode = $xml_data->addChild($key);
                $this->arrayToXml($value, $subNode);
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    /**
     * @param ServerRequestInterface           $request
     * @param ResponseInterface $response
     * @param callable|null     $next
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        /** @var ResponseInterface $response */
        $response = $next($request);

        if (!$this->isResponseFormattable->__invoke($response)) {
            return $response;
        }

        if (!$this->isValidAcceptType($request)) {
            return $response;
        }

        $dataModel = $this->getDataModel->__invoke($response, null);

        if (!is_array($dataModel)) {
            throw new \Exception(get_class($this) . ' requires dataModel to be an array');
        }

        $body = $response->getBody();

        $xmlData = new \SimpleXMLElement('<?xml version="1.0"?><data></data>');

        $content = null;

        if (is_array($dataModel)) {
            $this->arrayToXml($dataModel, $xmlData);

            $content = $xmlData->asXML();
        }

        $body->rewind();
        $body->write($content);

        return $response->withBody($body)->withHeader(
            'Content-Type',
            'application/xml'
        );
    }
}
