<?php

namespace Translator;

/**
 * @param string $text
 * @param string $source
 * @param string $target
 * @return Translate
 */
function translate(string $text, string $source, string $target = 'en'): Translate
{
    return Translator::translate($text, $source, $target);
}