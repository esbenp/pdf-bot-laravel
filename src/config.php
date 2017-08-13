<?php

return [
    'api_token' => env('PDF_BOT_API_TOKEN', ''),

    'secret' => env('PDF_BOT_SECRET', ''),

    'server' => env('PDF_BOT_SERVER', 'http://localhost:3000'),

    'header-namespace' => 'X-PDF-'
];
