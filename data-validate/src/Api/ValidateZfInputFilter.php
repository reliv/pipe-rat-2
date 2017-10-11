<?php

namespace Reliv\PipeRat2\DataValidate\Api;

use Psr\Container\ContainerInterface;
use Reliv\PipeRat2\DataValidate\ValidateResult\ValidateResult;
use Reliv\PipeRat2\DataValidate\ValidateResult\ValidateResultZfInputFilter;
use Reliv\PipeRat2\Options\Options;
use Zend\InputFilter\InputFilter;
use ZfInputFilterService\InputFilter\ServiceAwareFactory;
use ZfInputFilterService\InputFilter\ServiceAwareInputFilter;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValidateZfInputFilter implements Validate
{
    const OPTION_INPUT_FILTER_SERVICE_NAME = 'input-filter-service-name';
    const OPTION_INPUT_FILTER_CLASS = 'input-filter-class';
    const OPTION_INPUT_FILTER_CONFIG = 'input-filter-config';
    const OPTION_INPUT_CONTEXT = 'input-filter-context';
    const DEFAULT_PRIMARY_MESSAGE = 'An error occurred';

    protected $serviceContainer;
    protected $serviceAwareFactory;
    protected $defaultPrimaryMessage = self::DEFAULT_PRIMARY_MESSAGE;

    /**
     * @param ContainerInterface $serviceContainer
     * @param string             $defaultPrimaryMessage
     */
    public function __construct(
        $serviceContainer,
        string $defaultPrimaryMessage = self::DEFAULT_PRIMARY_MESSAGE
    ) {
        $this->serviceContainer = $serviceContainer;
        $this->serviceAwareFactory = $serviceContainer->get(ServiceAwareFactory::class);
        $this->defaultPrimaryMessage = $defaultPrimaryMessage;
    }

    /**
     * @param       $data
     * @param array $options
     *
     * @return ValidateResult
     * @throws \Exception
     */
    public function __invoke(
        $data,
        array $options = []
    ): ValidateResult
    {
        $context = Options::get(
            $options,
            self::OPTION_INPUT_CONTEXT,
            $data
        );

        $primaryMessage = Options::get(
            $options,
            self::OPTION_PRIMARY_MESSAGE,
            $this->defaultPrimaryMessage
        );

        $filterService = Options::get(
            $options,
            self::OPTION_INPUT_FILTER_SERVICE_NAME,
            ''
        );

        if (!empty($filterService)) {
            if (!$this->serviceContainer->has($filterService)) {
                throw new \Exception(
                    'InputFilter service in not a service: ' . $filterService
                );
            }
            /** @var InputFilter $inputFilter */
            $inputFilter = $this->serviceContainer->get($filterService);

            // NOTE: InputFilters can be stateful, so we clone
            $inputFilter = clone($inputFilter);

            $inputFilter->setData($data);

            $valid = $inputFilter->isValid($context);

            return new ValidateResultZfInputFilter(
                $valid,
                $inputFilter,
                $primaryMessage,
                $inputFilter->getValues()
            );
        }

        $filterClass = Options::get(
            $options,
            self::OPTION_INPUT_FILTER_CLASS,
            null
        );

        if (!empty($filterClass)) {
            if (!class_exists($filterClass)) {
                throw new \Exception(
                    'InputFilter class does not exist: ' . $filterClass
                );
            }
            /** @var InputFilter $inputFilter */
            $inputFilter = new $filterClass();

            $inputFilter->setData($data);

            $valid = $inputFilter->isValid($context);

            return new ValidateResultZfInputFilter(
                $valid,
                $inputFilter,
                $primaryMessage,
                $inputFilter->getValues()
            );
        }

        $filterConfig = Options::get(
            $options,
            self::OPTION_INPUT_FILTER_CONFIG,
            []
        );

        if (!empty($filterConfig)) {
            $inputFilter = new ServiceAwareInputFilter(
                $this->serviceAwareFactory,
                $filterConfig
            );

            $inputFilter->setData($data);

            $valid = $inputFilter->isValid($context);

            return new ValidateResultZfInputFilter(
                $valid,
                $inputFilter,
                $primaryMessage,
                $inputFilter->getValues()
            );
        }

        throw new \Exception(
            'No options set for ' . get_class($this)
        );
    }
}
