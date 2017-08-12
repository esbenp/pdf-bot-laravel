# pdf-bot-laravel

Laravel integration for [pdf-bot, a Node microservice for generating PDFs using headless Chrome](https://github.com/esbenp/pdf-bot).

## Installation

```bash
composer require optimus/pdf-bot 0.1.*
```

Add the service provider to `config/app.php`

```php
// ... other service providers
Optimus\PdfBot\PdfBotServiceProvider::class,
```

Publish the config file.

```bash
php artisan vendor:publish provider="Optimus\PdfBot\PdfBotServiceProvider"
```

You also need to create a controller for receiving webhooks.

```php
<?php

namespace Infrastructure\Webhooks\Controllers;

use Illuminate\Http\Request;
use Optimus\PdfBot\PdfBotController;

class PdfController extends PdfBotController
{
    protected function onPdfReceived(array $data, Request $request)
    {
        // Do stuff with PDF
    }
}
```

And add its route to your routes

```php
<?php
$router->post('/webhooks/pdf', '\Infrastructure\Webhooks\Controllers\PdfController@receive');
```

Now you can access `pdf-bot` using

```bash
$pdfBot = app()->make(\Optimus\PdfBot\PdfBot::class);
```

or by adding the including facade to your facades.

## Configuration

As a minimum you should change the `server` and `secret` parameters in `config/optimus.pdfbot.php`.

```php
<?php

return [
    'secret' => 'secret-used-for-generating-signature',

    'server' => 'http://url-to-pdf-bot-server',

    'header-namespace' => 'X-PDF-'
];
```

## Usage example

Now you are ready to generate PDF's. Start by pushing a URL to the PDF server for generation.

```php
$pdfBot = app()->make(\Optimus\PdfBot\PdfBot::class);
$pdfBot->push('https://invoices.traede.com/1', [
    'type' => 'invoice',
    'id' => 1
]);
```

Now this will be added to your PDF queue. At some point your PDF controller method (`onPdfReceived`) will receive a request with the path to the file. In your controller you can decide what to do with it.

```php
<?php

namespace Infrastructure\Webhooks\Controllers;

use Illuminate\Http\Request;
use Optimus\PdfBot\PdfBotController;

class PdfController extends PdfBotController
{
    protected function onPdfReceived(array $data, Request $request)
    {
        $meta = $data['meta'];
        $s3 = $data['s3'];

        switch ($meta['type']) {
            case 'invoice':
                $invoice = \Invoice::find($meta['id']);
                $invoice->pdf = $s3['path']['key'];
                $invoice->save();
                break;
        }
    }
}
```

Now the invoice's location is saved on the correct invoice.

## Issues

[Please report issues to the issue tracker](https://github.com/esbenp/pdf-bot/issues/new)

## License

The MIT License (MIT). Please see [License File](https://github.com/esbenp/pdf-bot/blob/master/LICENSE) for more information.
