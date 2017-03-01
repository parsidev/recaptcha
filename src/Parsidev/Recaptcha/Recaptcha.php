<?php

namespace Parsidev\Recaptcha;

class Recaptcha
{
    const SITE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    protected $service;
    protected $config = [];
    protected $dataParameterKeys = ['theme', 'type', 'callback', 'tabindex', 'expired-callback'];

    public function __construct($service, $config)
    {
        $this->service = $service;
        $this->config = $config;
    }

    public function render($options = [])
    {
        $mergedOptions = array_merge($this->config['options'], $options);
        $data = [
            'public_key' => value($this->config['public_key']),
            'options' => $mergedOptions,
            'dataParams' => $this->extractDataParams($mergedOptions),
        ];
        if (array_key_exists('lang', $mergedOptions) && "" !== trim($mergedOptions['lang'])) {
            $data['lang'] = $mergedOptions['lang'];
        }
        $view = $this->getView($options);
        return app('view')->make($view, $data);
    }

    protected function extractDataParams($options = [])
    {
        return array_only($options, $this->dataParameterKeys);
    }

    protected function getView($options = [])
    {
        $view = 'recaptcha::' . $this->service->getTemplate();
        $configTemplate = $this->config['template'];
        if (array_key_exists('template', $options)) {
            $view = $options['template'];
        } elseif ("" !== trim($configTemplate)) {
            $view = $configTemplate;
        }
        return $view;
    }

    public function verify($recaptchaResponse, $userIp = null, $version = null)
    {
        $peer_key = version_compare(PHP_VERSION, '5.6.0', '<') ? 'CN_name' : 'peer_name';
        $params = new RequestParameters($this->config['private_key'], $recaptchaResponse, $userIp, $version);
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => $params->toQueryString(),
                'verify_peer' => true,
                $peer_key => 'www.google.com',
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents(self::SITE_VERIFY_URL, false, $context);
        return Response::fromJson($result);
    }

}
