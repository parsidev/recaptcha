<?php

namespace Parsidev\Recaptcha;


interface RequestMethod
{
    /**
     * Submit the request with the specified parameters.
     *
     * @param RequestParameters $params Request parameters
     * @return string Body of the reCAPTCHA response
     */
    public function submit(RequestParameters $params);
}