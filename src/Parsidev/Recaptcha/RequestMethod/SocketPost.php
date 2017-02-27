<?php

namespace Parsidev\Recaptcha\RequestMethod;

use Parsidev\Recaptcha\RequestMethod;
use Parsidev\Recaptcha\RequestParameters;

class SocketPost implements RequestMethod
{
    const RECAPTCHA_HOST = 'www.google.com';
    const SITE_VERIFY_PATH = '/recaptcha/api/siteverify';
    const BAD_REQUEST = '{"success": false, "error-codes": ["invalid-request"]}';
    const BAD_RESPONSE = '{"success": false, "error-codes": ["invalid-response"]}';
    private $socket;

    public function __construct(Socket $socket = null)
    {
        if (!is_null($socket)) {
            $this->socket = $socket;
        } else {
            $this->socket = new Socket();
        }
    }

    public function submit(RequestParameters $params)
    {
        $errno = 0;
        $errstr = '';
        if (false === $this->socket->fsockopen('ssl://' . self::RECAPTCHA_HOST, 443, $errno, $errstr, 30)) {
            return self::BAD_REQUEST;
        }
        $content = $params->toQueryString();
        $request = "POST " . self::SITE_VERIFY_PATH . " HTTP/1.1\r\n";
        $request .= "Host: " . self::RECAPTCHA_HOST . "\r\n";
        $request .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $request .= "Content-length: " . strlen($content) . "\r\n";
        $request .= "Connection: close\r\n\r\n";
        $request .= $content . "\r\n\r\n";
        $this->socket->fwrite($request);
        $response = '';
        while (!$this->socket->feof()) {
            $response .= $this->socket->fgets(4096);
        }
        $this->socket->fclose();
        if (0 !== strpos($response, 'HTTP/1.1 200 OK')) {
            return self::BAD_RESPONSE;
        }
        $parts = preg_split("#\n\s*\n#Uis", $response);
        return $parts[1];
    }
}