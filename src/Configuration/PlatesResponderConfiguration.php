<?php

namespace Minormous\Framework\Configuration;

use Auryn\Injector;
use Minormous\Framework\Formatter\PlatesFormatter;
use Minormous\Framework\Responder\FormattedResponder;

class PlatesResponderConfiguration implements ConfigurationInterface
{
    public function apply(Injector $injector)
    {
        $injector->prepare(FormattedResponder::class, function (FormattedResponder $responder) {
            return $responder->withValue(PlatesFormatter::class, 1.0);
        });
    }
}
