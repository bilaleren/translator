# PHP Google Translate
PHP Google Translate library.

## Prerequisites

PHP >= 7.1

## Installation

```
composer require bilaleren/translator
```

## Usage

```php
use Translator\Translator;

echo Translator::translate('Hello world', 'tr');

// Or

use function Translator\translate;

echo translate('Hello world', 'tr');
```
