<?php

namespace {

    use Cartapia\FormBuilder\Events\AfterFieldCreation;
    use Cartapia\FormBuilder\Events\AfterFormCreation;
    use Cartapia\FormBuilder\Form;
    use Cartapia\FormBuilder\FormBuilder;
    use Cartapia\FormBuilder\FormHelper;

    class FormBuilderValidationTest extends FormBuilderTestCase
    {
        public function setUp(): void
        {
            parent::setUp();
            $this->app
                ->make('Illuminate\Contracts\Http\Kernel')
                ->pushMiddleware('Illuminate\Session\Middleware\StartSession');
        }

        public function testItValidatesWhenResolved()
        {
            Route::post('/test', TestController::class.'@validate');

            $this->post('/test', ['email' => 'foo@bar.com'])
                ->assertRedirect('/')
                ->assertSessionHasErrors(['name']);
        }

        public function testItDoesNotValidateGetRequests()
        {
            Route::get('/test', TestController::class.'@validate');

            $this->get('/test', ['email' => 'foo@bar.com'])
                ->assertStatus(200);
        }
    }
}
