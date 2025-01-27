<?php

namespace Cartapia\FormBuilder;

trait FormBuilderTrait
{

    /**
     * Create a Form instance.
     *
     * @param string $name Full class name of the form class.
     * @param array  $options Options to pass to the form.
     * @param array  $data additional data to pass to the form.
     *
     * @return \Cartapia\FormBuilder\Form
     */
    protected function form($name, array $options = [], array $data = [])
    {
        return \App::make('form-builder')->create($name, $options, $data);
    }

    /**
     * Create a plain Form instance.
     *
     * @param array $options Options to pass to the form.
     * @param array $data additional data to pass to the form.
     *
     * @return \Cartapia\FormBuilder\Form
     */
    protected function plain(array $options = [], array $data = [])
    {
        return \App::make('form-builder')->plain($options, $data);
    }
}
