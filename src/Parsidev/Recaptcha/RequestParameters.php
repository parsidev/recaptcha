<?php
namespace Parsidev\Recaptcha;


class RequestParameters
{
    private $secret;
    private $response;
    private $remoteIp;
    private $version;

    public function __construct($secret, $response, $remoteIp = null, $version = null)
    {
        $this->secret = $secret;
        $this->response = $response;
        $this->remoteIp = $remoteIp;
        $this->version = $version;
    }

    public function toQueryString()
    {
        return http_build_query($this->toArray(), '', '&');
    }

    public function toArray()
    {
        $params = array('secret' => $this->secret, 'response' => $this->response);
        if (!is_null($this->remoteIp)) {
            $params['remoteip'] = $this->remoteIp;
        }
        if (!is_null($this->version)) {
            $params['version'] = $this->version;
        }
        return $params;
    }
}
