<?php

namespace CoRex\Client\Rest;

use CoRex\Client\Http\Client as HttpClient;
use CoRex\Client\Http\RequestInterface;

class Client extends HttpClient
{
    /**
     * GET.
     *
     * @param RequestInterface|null $request
     * @param string $responseClass Default null.
     * @return Response
     */
    public function get(RequestInterface $request = null, $responseClass = null)
    {
        return $this->prepareResponse(parent::get($request), $responseClass);
    }

    /**
     * POST.
     *
     * @param RequestInterface|null $request
     * @param string $responseClass Default null.
     * @return Response
     */
    public function post(RequestInterface $request = null, $responseClass = null)
    {
        return $this->prepareResponse(parent::post($request), $responseClass);
    }

    /**
     * PUT.
     *
     * @param RequestInterface|null $request
     * @param string $responseClass Default null.
     * @return Response
     */
    public function put(RequestInterface $request = null, $responseClass = null)
    {
        return $this->prepareResponse(parent::put($request), $responseClass);
    }

    /**
     * DELETE.
     *
     * @param RequestInterface|null $request
     * @param string $responseClass Default null.
     * @return Response
     */
    public function delete(RequestInterface $request = null, $responseClass = null)
    {
        return $this->prepareResponse(parent::delete($request), $responseClass);
    }

    /**
     * Prepare response.
     *
     * @param Response $response
     * @param string $responseClass
     * @return Response
     * @throws Exception
     */
    private function prepareResponse($response, $responseClass = null)
    {
        if ($response !== null) {
            if ($responseClass !== null) {
                if (!in_array(ResponseInterface::class, class_implements($responseClass))) {
                    throw new Exception('Response class does not implement ' . ResponseInterface::class . '.');
                }
                $response = new $responseClass($response);
            } else {
                $response = new Response($response);
            }
        }
        return $response;
    }
}