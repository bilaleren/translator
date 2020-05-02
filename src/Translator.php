<?php

namespace Translator;

final class Translator
{

    /**
     * @param string $text
     * @param string $source
     * @param string $target
     * @return Translate
     */
    public static function translate(string $text, string $source, string $target = 'en'): Translate
    {
        return new Translate($text, $source, $target);
    }

}
