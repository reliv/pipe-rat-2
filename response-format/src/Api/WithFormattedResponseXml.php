<?php

namespace Reliv\PipeRat2\ResponseFormat\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Core\Api\GetDataModel;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithFormattedResponseXml implements WithFormattedResponse
{
    const OPTION_FORMATTABLE_RESPONSE_CLASSES = IsResponseFormattable::OPTION_FORMATTABLE_RESPONSE_CLASSES;
    const OPTION_CONTENT_TYPE = 'content-type';

    const DEFAULT_FORMATTABLE_RESPONSE_CLASSES = IsResponseFormattable::DEFAULT_FORMATTABLE_RESPONSE_CLASSES;
    const DEFAULT_CONTENT_TYPE = 'application/xml';

    protected $isResponseFormattable;
    protected $getDataModel;
    protected $defaultContentType;
    protected $defaultFormattableResponseClasses;

    /**
     * @param IsResponseFormattable $isResponseFormattable
     * @param GetDataModel          $getDataModel
     * @param string                $defaultContentType
     * @param array                 $defaultFormattableResponseClasses
     */
    public function __construct(
        IsResponseFormattable $isResponseFormattable,
        GetDataModel $getDataModel,
        string $defaultContentType = self::DEFAULT_CONTENT_TYPE,
        array $defaultFormattableResponseClasses = self::DEFAULT_FORMATTABLE_RESPONSE_CLASSES
    ) {
        $this->isResponseFormattable = $isResponseFormattable;
        $this->getDataModel = $getDataModel;
        $this->defaultContentType = $defaultContentType;
        $this->defaultFormattableResponseClasses = $defaultFormattableResponseClasses;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $options
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $options = []
    ): ResponseInterface
    {
        if (!$this->isResponseFormattable->__invoke($response)) {
            return $response;
        }

        $dataModel = $this->getDataModel->__invoke($response);

        if (!is_array($dataModel)) {
            throw new \Exception(
                get_class($this) . ' requires dataModel to be an array, got:' . var_export($dataModel, true)
            );
        }

        $body = $response->getBody();

        $xmlData = new \SimpleXMLElement('<?xml version="1.0"?><data></data>');

        $content = null;

        $this->arrayToXml($dataModel, $xmlData);

        $content = $xmlData->asXML();

        $body->rewind();
        $body->write($content);

        $contentType = Options::get(
            $options,
            self::OPTION_CONTENT_TYPE,
            $this->defaultContentType
        );

        return $response->withBody($body)->withHeader(
            'Content-Type',
            $contentType
        );
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
}
