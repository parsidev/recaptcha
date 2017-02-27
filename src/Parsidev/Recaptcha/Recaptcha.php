<?php

namespace Parsidev\Recaptcha;

class Recaptcha
{
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

    protected function extractDataParams($options = [])
    {
        return array_only($options, $this->dataParameterKeys);
    }


}