<?php

namespace CoRex\Client\Base;

abstract class Connector implements ConnectorInterface
{
    /**
     * @var ConnectorRequest
     */
    protected $request;

    /**
     * Build url.
     *
     * @return string
     */
    protected function buildUrl()
    {
        $url = $this->request->url;

        // Replace tokens.
        if (is_array($this->request->tokens)) {
            foreach ($this->request->tokens as $token => $value) {
                $url = str_replace('{' . $token . '}', $value, $url);
            }
        }

        // Add parameters.
        $parameters = [];
        if (count($parameters) > 0 && is_array($this->request->parameters)) {
            $url .= strpos($url, '?') > 0 ? '&' : '?';
            foreach ($this->request->parameters as $name => $value) {
                $parameters[$name] = urlencode($value);
            }
            $url .= implode('&', $parameters);
        }

        return $url;
    }
}