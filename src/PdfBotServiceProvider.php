<?php

namespace Optimus\PdfBot;

use Illuminate\Support\ServiceProvider as BaseProvider;
use Optimus\PdfBot\PdfBot;

class PdfBotServiceProvider extends BaseProvider {

    public function register()
    {
        $this->loadConfig();
        $this->registerAssets();
        $this->bindInstance();
    }

    public function bindInstance()
    {
        $this->app->singleton(PdfBot::class, function(){
            $bot = new PdfBot();
            return $bot;
        });
    }

    private function registerAssets()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path('optimus.pdfbot.php')
        ]);
    }

    private function loadConfig()
    {
        if ($this->app['config']->get('optimus.pdfbot') === null) {
            $this->app['config']->set('optimus.pdfbot', require __DIR__.'/config.php');
        }
    }
}
