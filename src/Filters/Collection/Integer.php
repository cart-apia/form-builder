<?php

namespace Cartapia\FormBuilder\Filters\Collection;

use Cartapia\FormBuilder\Filters\FilterInterface;

/**
 * Class Integer
 *
 * @package Cartapia\FormBuilder\Filters\Collection
 * @author  Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
class Integer implements FilterInterface
{
    /**
     * @param mixed $value
     * @param array $options
     *
     * @return mixed
     */
    public function filter($value, $options = [])
    {
       $value = (int) ((string) $value);
       return $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Integer';
    }
}