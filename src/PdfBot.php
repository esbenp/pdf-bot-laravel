<?php

namespace Optimus\PdfBot;

use GuzzleHttp\Client;

class PdfBot
{
    public function push($url, array $meta = [], $priority = null, array $configOverrides = [])
    {
        $resolveConfig = function ($key) use ($configOverrides) {
            return !empty($configOverrides[$key]) ? $configOverrides[$key] : config(sprintf('optimus.pdfbot.%s', $key));
        };

        $headers = [];
        $apiToken = $resolveConfig('api_token');

        if (!empty($apiToken)) {
            $headers['authorization'] = 'Bearer ' . $apiToken;
        }

        $body = [
            'url' => $url,
            'meta' => (object) $meta
        ];

        if (is_integer($priority)) {
            $body['priority'] = $priority;
        }

        $server = $resolveConfig('server');

        $client = new Client();
        return $client->request('POST', $server, [
            'headers' => $headers,
            'json' => $body
        ]);
    }
}
