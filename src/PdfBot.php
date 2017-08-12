<?php

namespace Optimus\PdfBot;

use GuzzleHttp\Client;

class PdfBot
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function push($url, array $meta = [])
    {
        $client = new Client();
        return $client->request('POST', $this->config['server'], [
            'json' => [
                'url' => $url,
                'meta' => (object) $meta
            ]
        ]);
    }
}
