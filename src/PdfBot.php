<?php

namespace Optimus\PdfBot;

use GuzzleHttp\Client;

class PdfBot
{
    public function push($url, array $meta = [], $priority = null)
    {
        $config = config('optimus.pdfbot');

        $headers = [];

        if (!empty($config['api_token'])) {
            $headers['authorization'] = 'Bearer ' . $config['api_token'];
        }

        $body = [
            'url' => $url,
            'meta' => (object) $meta
        ];

        if (is_integer($priority)) {
            $body['priority'] = $priority;
        }

        $client = new Client();
        return $client->request('POST', $config['server'], [
            'headers' => $headers,
            'json' => $body
        ]);
    }
}
