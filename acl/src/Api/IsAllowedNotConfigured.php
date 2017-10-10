<?php

namespace Reliv\PipeRat2\Acl\Api;

use Psr\Http\Message\ServerRequestInterface;
use Reliv\PipeRat2\Options\Options;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedNotConfigured implements IsAllowed
{
    const OPTION_MESSAGE = 'message';
    const DEFAULT_MESSAGE = 'Acl has not be configured';
    const DEFAULT_ERROR_TYPE = E_USER_WARNING;

    protected $defaultMessage;
    protected $errorType;

    /**
     * @param string|null $defaultMessage
     * @param int         $errorType
     */
    public function __construct(
        string $defaultMessage = self::DEFAULT_MESSAGE,
        int $errorType = self::DEFAULT_ERROR_TYPE
    ) {
        $this->defaultMessage = $defaultMessage;
        $this->errorType = $errorType;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return bool
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ): bool
    {
        $message = Options::get(
            $options,
            self::OPTION_MESSAGE,
            $this->defaultMessage
        );

        if ($this->errorType > 0) {
            trigger_error(
                $message,
                $this->errorType
            );
        }

        return true;
    }
}
