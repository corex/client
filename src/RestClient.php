<?php

namespace CoRex\Client;

class RestClient
{
    private $client;
    private $response;
    private $responseRaw;

    /**
     * RestClient constructor.
     */
    public function __construct()
    {
        $this->client = new HttpClient();
        $this->client->setContentType('application/json');
        $this->client->setAcceptType('application/json');
        $this->response = [];
        $this->responseRaw = '';
    }

    /**
     * Get http client.
     *
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->client;
    }

    /**
     * Set url.
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->client->setUrl($url);
        return $this;
    }

    /**
     * Set path field.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setPathField($name, $value)
    {
        $this->client->setPathField($name, $value);
        return $this;
    }

    /**
     * Set query field.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setQueryField($name, $value)
    {
        $this->client->setQueryField($name, $value);
        return $this;
    }

    /**
     * Set request field.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setRequestField($name, $value)
    {
        $this->client->setPostField($name, $value);
        return $this;
    }

    /**
     * Set request array.
     *
     * @param array $array
     * @return $this
     */
    public function setRequestArray(array $array)
    {
        $this->client->setPostArray($array);
        return $this;
    }

    /**
     * POST.
     *
     * @return boolean
     */
    public function post()
    {
        if ($this->client->post()) {
            return $this->setResponse($this->client->getResponse());
        }
        return false;
    }

    /**
     * GET.
     *
     * @return boolean
     */
    public function get()
    {
        if ($this->client->get()) {
            return $this->setResponse($this->client->getResponse());
        }
        return false;
    }

    /**
     * PUT.
     *
     * @return boolean
     */
    public function put()
    {
        if ($this->client->put()) {
            return $this->setResponse($this->client->getResponse());
        }
        return false;
    }

    /**
     * DELETE.
     *
     * @return boolean
     */
    public function delete()
    {
        if ($this->client->delete()) {
            return $this->setResponse($this->client->getResponse());
        }
        return false;
    }

    /**
     * Get response.
     *
     * @param string $path Default '' which means all. Possible to use dot notation.
     * @param mixed $defaultValue Default null.
     * @return array|mixed|null
     */
    public function getResponse($path = '', $defaultValue = null)
    {
        if ($path != '') {
            $response = $this->response;
            $path = explode('.', $path);
            foreach ($path as $key) {
                $response = $response[$key];
            }
            if ($response === null) {
                $response = $defaultValue;
            }
            return $response;
        } else {
            return $this->response;
        }
    }

    /**
     * Get response raw.
     *
     * @return string
     */
    public function getResponseRaw()
    {
        return $this->responseRaw;
    }

    /**
     * Set response.
     *
     * @param string $response
     * @return boolean
     */
    private function setResponse($response)
    {
        $this->responseRaw = $response;
        if (!in_array(substr($response, 0, 1), ['[', '{'])) {
            return false;
        }
        $this->response = json_decode($response, true);
        return true;
    }
}