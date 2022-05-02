<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Traits\Translation;


class TranslationTest extends TestCase
{
    public function test_translate()
    {
        $mockObject = $this->getObjectForTrait(Translation::class);

        $en = $mockObject->translateObject('{"en":"hello", "uk":"вітаю"}', 'en');
        $this->assertEquals("hello", $en);

        $uk = $mockObject->translateObject('{"en":"hello", "uk":"вітаю"}', 'uk');
        $this->assertEquals("вітаю", $uk);
    }
}

