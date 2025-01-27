<?php

namespace {

    use Cartapia\FormBuilder\Events\AfterFieldCreation;
    use Cartapia\FormBuilder\Events\AfterFormCreation;
    use Cartapia\FormBuilder\Form;
    use Cartapia\FormBuilder\FormBuilder;
    use Cartapia\FormBuilder\FormHelper;

    class FormBuilderTest extends FormBuilderTestCase
    {

        /** @test */
        public function it_creates_plain_form_and_sets_options_on_it()
        {
            $options = [
                'method' => 'PUT',
                'url' => '/some/url/1',
                'model' => $this->model
            ];

            $plainForm = $this->formBuilder->plain($options);

            $this->assertEquals('PUT', $plainForm->getMethod());
            $this->assertEquals('/some/url/1', $plainForm->getUrl());
            $this->assertEquals($this->model, $plainForm->getModel());
            $this->assertNull($plainForm->buildForm());
        }

        /** @test */
        public function it_creates_form_with_array_and_compares_it_with_created_form_by_class()
        {
            $form = $this->formBuilder->create(CustomDummyForm::class);
            $arrayForm = $this->formBuilder->createByArray([
                [
                    'type' => 'text',
                    'name' => 'title',
                ],
                [
                    'type' => 'textarea',
                    'name' => 'body',
                ]
            ]);

            $this->assertEquals($form->getField('title')->getType(), $arrayForm->getField('title')->getType());
            $this->assertEquals($form->getField('body')->getType(), $arrayForm->getField('body')->getType());
        }

        /** @test */
        public function it_creates_custom_form_and_sets_options_on_it()
        {
            $options = [
                'method' => 'POST',
                'url' => '/posts',
                'data' => ['dummy_choices' => [1 => 'choice_1', 2 => 'choice_2']]
            ];

            $customForm = $this->formBuilder->create('CustomDummyForm', $options);

            $this->assertEquals('POST', $customForm->getMethod());
            $this->assertEquals($this->request, $customForm->getRequest());
            $this->assertEquals('/posts', $customForm->getUrl());
            $this->assertEquals([1 => 'choice_1', 2 => 'choice_2'], $customForm->getData('dummy_choices'));
            $this->assertInstanceOf('Cartapia\\FormBuilder\\Form', $customForm);
            $this->assertArrayHasKey('title', $customForm->getFields());
            $this->assertArrayHasKey('body', $customForm->getFields());
        }

        /** @test */
        public function it_receives_creation_events()
        {
            $events = [];

            $this->eventDispatcher->listen(AfterFormCreation::class, function($event) use (&$events) {
                $events[] = get_class($event);
            });

            $this->eventDispatcher->listen(AfterFieldCreation::class, function($event) use (&$events) {
                $events[] = get_class($event);
            });

            $form = $this->formBuilder->plain()
                ->add('name', 'text', ['rules' => ['required', 'min:3']])
                ->add('alias', 'text');

            $form = $this->formBuilder->create('CustomDummyForm');

            $this->assertEquals(
                [
                    // Plain: form first
                    'Cartapia\FormBuilder\Events\AfterFormCreation',
                    'Cartapia\FormBuilder\Events\AfterFieldCreation',
                    'Cartapia\FormBuilder\Events\AfterFieldCreation',

                    // Class: fields first
                    'Cartapia\FormBuilder\Events\AfterFieldCreation',
                    'Cartapia\FormBuilder\Events\AfterFieldCreation',
                    'Cartapia\FormBuilder\Events\AfterFormCreation',
                ],
                $events
            );
        }

        /**
         * @test
         */
        public function it_throws_exception_if_child_form_is_not_valid_class()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->plainForm->add('song', 'form', [
                'class' => 'nonvalid'
            ]);
        }

        /**
         * @test
         */
        public function it_throws_exception_if_child_form_class_is_not_passed()
        {
            $this->expectException(\InvalidArgumentException::class);

            $this->plainForm->add('song', 'form', [
                'class' => null
            ]);
        }

        /**
         * @test
         */
        public function it_throws_exception_if_child_form_class_is_not_valid_format()
        {
            $this->expectException(\InvalidArgumentException::class);

            $this->plainForm->add('song', 'form', [
                'class' => 1
            ]);
        }

        /** @test */
        public function it_can_set_form_helper_once_and_call_build_form()
        {
            $form = $this->formBuilder->create('CustomDummyForm');

            $this->assertEquals($this->formHelper, $form->getFormHelper());
            $this->assertEquals($this->formBuilder, $form->getFormBuilder());
            $this->assertArrayHasKey('title', $form->getFields());
            $this->assertArrayHasKey('body', $form->getFields());
        }

        /** @test */
        public function it_appends_default_namespace_from_config_on_building()
        {
            $form =  new LaravelFormBuilderTest\Forms\NamespacedDummyForm();
            $config = $this->config;
            $config['default_namespace'] = 'LaravelFormBuilderTest\Forms';
            $formHelper = new FormHelper($this->view, $this->translator, $config);
            $formBuilder = new FormBuilder($this->app, $formHelper, $this->app['events']);

            $formBuilder->create('NamespacedDummyForm');

            $this->assertNotThrown();
        }

    }
}

namespace LaravelFormBuilderTest\Forms {

    use Cartapia\FormBuilder\Form;

    class NamespacedDummyForm extends Form
    {
    }
}
