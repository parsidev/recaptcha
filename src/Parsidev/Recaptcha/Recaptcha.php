<?php

namespace Parsidev\Recaptcha;

use Parsidev\Recaptcha\RequestMethod\Post;

class Recaptcha
{
    const VERSION = 'php_1.1.2';
    protected $secret;
    private $requestMethod;

    public function __construct($secret, RequestMethod $requestMethod = null)
    {
        if (empty($secret)) {
            throw new \RuntimeException('No secret provided');
        }
        if (!is_string($secret)) {
            throw new \RuntimeException('The provided secret must be a string');
        }
        $this->secret = $secret;

        if (!is_null($requestMethod)) {
            $this->requestMethod = $requestMethod;
        } else {
            $this->requestMethod = new Post();
        }

    }


}