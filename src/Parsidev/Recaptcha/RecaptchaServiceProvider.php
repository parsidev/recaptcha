<?php

namespace Parsidev\Recaptcha;

use Illuminate\Support\ServiceProvider;

class RecaptchaServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->addValidator();
        $this->loadViewsFrom(__DIR__ . '/views', 'recaptcha');
    }

    public function addValidator()
    {
        $this->app->validator->extendImplicit('recaptcha', function ($attribute, $value, $parameters) {
            $captcha = app('recaptcha.service');
            $challenge = app('request')->input($captcha->getResponseKey());
            return $captcha->check($challenge, $value);
        }, 'Please ensure that you are a human!');
    }

    public function register()
    {
        $this->bindRecaptcha();
        $this->handleConfig();
    }

    protected function bindRecaptcha()
    {
        $this->app->bind('recaptcha.service', function () {
            if (app('config')->get('recaptcha.version', false) === 2 || app('config')->get('recaptcha.v2', false)) {
                return new Service\CheckRecaptchaV2;
            }
            return new Service\CheckRecaptcha;
        });
        $this->app->bind('recaptcha', function () {
            return new Recaptcha($this->app->make('recaptcha.service'), app('config')->get('recaptcha'));
        });
    }

    protected function handleConfig()
    {
        $packageConfig = __DIR__ . '/config/recaptcha.php';
        $destinationConfig = config_path('recaptcha.php');
        $this->publishes([
            $packageConfig => $destinationConfig,
        ]);
    }

    public function provides()
    {
        return [
            'recaptcha',
        ];
    }

}