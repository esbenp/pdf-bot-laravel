<?php

namespace Optimus\PdfBot;

use Illuminate\Support\Facades\Facade;
use Optimus\PdfBot\PdfBot;

class PdfBotFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PdfBot::class;
    }
}
