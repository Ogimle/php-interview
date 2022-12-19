<?php

interface RequestServiceInterface
{
    public function request(string $url, string $method, array $options = null);
}

class XMLHttpService implements RequestServiceInterface
{
    public function request(string $url, string $method, array $options = null)
    {
        // todo:
    }
}

class Http
{
    private $service;

    public function __construct(RequestServiceInterface $service)
    {
        $this->service = $service;
    }

    public function get(string $url, array $options)
    {
        $this->service->request($url, 'GET', $options);
    }

    public function post(string $url)
    {
        $this->service->request($url, 'GET');
    }
}
