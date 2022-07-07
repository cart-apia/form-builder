<?php

return [
    'defaults'      => [
        'wrapper_class'       => 'form-group',
        'wrapper_error_class' => 'has-error',
        'label_class'         => 'control-label',
        'field_class'         => 'form-control',
        'field_error_class'   => '',
        'help_block_class'    => 'help-block',
        'error_class'         => 'text-danger',
        'required_class'      => 'required',

        'help_block_tag'      => 'p',

        // Override a class from a field.
        //'text'                => [
        //    'wrapper_class'   => 'form-field-text',
        //    'label_class'     => 'form-field-text-label',
        //    'field_class'     => 'form-field-text-field',
        //]
        //'radio'               => [
        //    'choice_options'  => [
        //        'wrapper'     => ['class' => 'form-radio'],
        //        'label'       => ['class' => 'form-radio-label'],
        //        'field'       => ['class' => 'form-radio-field'],
        //],
    ],
    // Templates
    'form'          => 'form-builder::form',
    'text'          => 'form-builder::text',
    'textarea'      => 'form-builder::textarea',
    'button'        => 'form-builder::button',
    'buttongroup'   => 'form-builder::buttongroup',
    'radio'         => 'form-builder::radio',
    'checkbox'      => 'form-builder::checkbox',
    'select'        => 'form-builder::select',
    'choice'        => 'form-builder::choice',
    'repeated'      => 'form-builder::repeated',
    'child_form'    => 'form-builder::child_form',
    'collection'    => 'form-builder::collection',
    'static'        => 'form-builder::static',

    // Remove the form-builder:: prefix above when using template_prefix
    'template_prefix'   => '',

    'default_namespace' => '',

    'custom_fields' => [
//        'datetime' => App\Forms\Fields\Datetime::class
    ],

    'plain_form_class' => \Cartapia\FormBuilder\Form::class,
    'form_builder_class' => \Cartapia\FormBuilder\FormBuilder::class,
    'form_helper_class' => \Cartapia\FormBuilder\FormHelper::class,
];
