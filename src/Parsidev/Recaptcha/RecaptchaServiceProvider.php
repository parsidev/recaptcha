<?php

namespace Parsidev\Recaptcha;

use Illuminate\Support\ServiceProvider;

class RecaptchaServiceProvider extends ServiceProvider {
    protected $defer = true;

    public function boot() {
        $this->publishes([
            __DIR__ . '/../../config/recaptcha.php' => config_path('recaptcha.php'),
        ]);
    }

    public function register() {
        $this->app->singleton('recaptcha', function() {
            $config = config('novinways');
            return new Recaptcha($config('secretKey'));
        });
    }

    public function provides() {
        return ['recaptcha'];
    }

}