<?php

namespace  Cartapia\FormBuilder\Fields;

class SelectType extends FormField
{

    /**
     * The name of the property that holds the value.
     *
     * @var string
     */
    protected $valueProperty = 'selected';

     /**
     * @inheritdoc
     */
    protected function getTemplate()
    {
        return 'select';
    }

    /**
     * @inheritdoc
     */
    public function getDefaults()
    {
        return [
            'choices' => [],
            'option_attributes' => [],
            'empty_value' => null,
            'selected' => null
        ];
    }
}
