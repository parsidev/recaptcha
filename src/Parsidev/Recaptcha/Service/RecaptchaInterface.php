<?php

namespace Parsidev\Recaptcha\Service;


interface RecaptchaInterface
{
    public function check($challenge, $response);

    public function getTemplate();

    public function getResponseKey();
}