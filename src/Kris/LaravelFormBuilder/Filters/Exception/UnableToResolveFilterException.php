<?php

namespace Cartapia\FormBuilder\Filters\Exception;

use Throwable;

/**
 * Class UnableToResolveFilterException
 *
 * @package Cartapia\FormBuilder\Filters\Exception
 * @author  Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
class UnableToResolveFilterException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = "Passed filter can't be resolved.";
        parent::__construct($message, $code, $previous);
    }
}