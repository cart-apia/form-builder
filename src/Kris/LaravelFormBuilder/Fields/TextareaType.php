<?php

namespace Cartapia\FormBuilder\Fields;

class TextareaType extends FormField
{

    /**
     * @inheritdoc
     */
    protected function getTemplate()
    {
        return 'textarea';
    }
}
