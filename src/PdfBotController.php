<?php

namespace Optimus\PdfBot;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Optimus\PdfBot\Exceptions\PdfBotAuthenticationException;

abstract class PdfBotController extends BaseController
{
    abstract protected function onPdfReceived(array $data, Request $request);

    public function receive(Request $request)
    {
        $config = config('optimus.pdfbot');
        $secret = $config['secret'];

        if (!empty($secret)) {
            $header = sprintf('%sSignature', $config['header-namespace']);

            $signature = $request->headers->get(mb_strtolower($header), false);

            if (!$signature) {
                throw new PdfBotAuthenticationException(
                    'No signature found in response. Is your header-namespace configured correctly?'
                );
            }

            $encrypted = hash_hmac('sha1', $request->getContent(), $secret);

            if ($encrypted !== $signature) {
                throw new PdfBotAuthenticationException('Authentication failed.');
            }
        }

        $this->onPdfReceived($request->all(), $request);
    }
}
