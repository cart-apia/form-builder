<?php

namespace Cartapia\FormBuilder;

use Illuminate\Foundation\AliasLoader;
use Collective\Html\FormBuilder as LaravelForm;
use Collective\Html\HtmlBuilder;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Cartapia\FormBuilder\Traits\ValidatesWhenResolved;
use Cartapia\FormBuilder\Form;

class FormBuilderServiceProvider extends ServiceProvider
{
    protected const HTML_ABSTRACT = 'html';
    protected const FORM_ABSTRACT = 'form';
    protected const BUILDER_ABSTRACT = 'form-builder';
    protected const HELPER_ABSTRACT = 'laravel-form-helper';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands('Cartapia\FormBuilder\Console\FormMakeCommand');

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/config/config.php',
            'form-builder'
        );

        $this->registerFormHelper();
        $this->registerFormBuilder();
    }

    /**
     * Register the form helper.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $abstract = static::BUILDER_ABSTRACT;

        $formBuilderClass = $this->getFormBuilderClass();

        $this->app->singleton($abstract, function ($app) use ($formBuilderClass) {
            $formBuilder = new $formBuilderClass($app, $app[static::HELPER_ABSTRACT], $app['events']);
            $formBuilder->setFormClass($this->getPlainFormClass());
            return $formBuilder;
        });

        $this->app->alias($abstract, $formBuilderClass);
        if ($formBuilderClass != FormBuilder::class) {
            $this->app->alias($abstract, FormBuilder::class);
        }

        $this->app->afterResolving(Form::class, function ($object, $app) use ($abstract) {
            $request = $app->make('request');

            if (in_array(ValidatesWhenResolved::class, class_uses($object), true) && $request->method() !== 'GET') {
                $form = $app->make($abstract)->setDependenciesAndOptions($object);
                $form->buildForm();
                $form->redirectIfNotValid();
            }
        });
    }

    /**
     * Register the form helper.
     *
     * @return void
     */
    protected function registerFormHelper()
    {
        $abstract = static::HELPER_ABSTRACT;

        $formHelperClass = $this->getFormHelperClass();

        $this->app->singleton($abstract, function ($app) use ($formHelperClass) {
            $configuration = $app['config']->get('form-builder');

            return new $formHelperClass($app['view'], $app['translator'], $configuration);
        });

        $this->app->alias($abstract, $formHelperClass);
        if ($formHelperClass != FormHelper::class) {
            $this->app->alias($abstract, FormHelper::class);
        }
    }

    /**
     * Bootstrap the service.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../views', 'form-builder');

        $this->publishes([
            __DIR__ . '/../views' => base_path('resources/views/vendor/form-builder'),
            __DIR__ . '/../config/config.php' => config_path('form-builder.php')
        ]);

        $form = $this->app[static::FORM_ABSTRACT];

        $form->macro('customLabel', function($name, $value, $options = [], $escape_html = true) use ($form) {
            if (isset($options['for']) && $for = $options['for']) {
                unset($options['for']);
                return $form->label($for, $value, $options, $escape_html);
            }

            return $form->label($name, $value, $options, $escape_html);
        });
    }

    /**
     * Get the services provided by this provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return ['form-builder'];
    }

    /**
     * @return class-string
     */
    protected function getPlainFormClass()
    {
        return $this->app['config']->get('form-builder.plain_form_class', Form::class);
    }

    /**
     * @return class-string
     */
    protected function getFormBuilderClass()
    {
        $expectedClass = FormBuilder::class;
        $defaultClass = FormBuilder::class;

        $class = $this->app['config']->get('form-builder.form_builder_class', $defaultClass);

        if (!class_exists($class)) {
            throw new InvalidArgumentException("Class {$class} does not exist");
        }

        if ($class !== $expectedClass && !is_subclass_of($class, $expectedClass)) {
            throw new InvalidArgumentException("Class {$class} must extend " . $expectedClass);
        }

        return $class;
    }

    /**
     * @return class-string
     */
    protected function getFormHelperClass()
    {
        $expectedClass = FormHelper::class;
        $defaultClass = FormHelper::class;

        $class = $this->app['config']->get('form-builder.form_helper_class', $defaultClass);

        if (!class_exists($class)) {
            throw new InvalidArgumentException("Class {$class} does not exist");
        }

        if ($class !== $expectedClass && !is_subclass_of($class, $expectedClass)) {
            throw new InvalidArgumentException("Class {$class} must extend " . $expectedClass);
        }

        return $class;
    }
}
