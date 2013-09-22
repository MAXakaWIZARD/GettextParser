# gettext parser for poEdit
[![Build Status](https://travis-ci.org/auraphp/MAXakaWIZARD.GettextParser.png?branch=dev)](https://travis-ci.org/MAXakaWIZARD/GettextParser)

Library for syncing gettext catalogs with Smarty and Javascript sources.

This package is compliant with [PSR-0](http://www.php-fig.org/psr/0/), [PSR-1](http://www.php-fig.org/psr/1/), and [PSR-2](http://www.php-fig.org/psr/2/).
If you notice compliance oversights, please send a patch via pull request.

## Installation
Just download sources and unpack to any folder.

## Supported formats
### JavaScript
jQuery.gettext plugin:
```javascript
_('Text to be localized')
n_('country', 'countries', 3);
```

### Smarty
block.t plugin:
```
{t}Text to be localized{/t}
```
native:
```
{"Text to be localized"|_}
{_("Text to be localized")}
```

## Usage
1. Create new parser: `File->Preferences->Parsers->New`
2. Update your gettext catalog: `Catalogue->Update from sources`

### Parser params:

#### Smarty
* Language: `Smarty`
* Parser command: `php.exe -f "/path/to/GettextParser/index.php" Smarty %o %C %K %F`
* List of extensions: `*.tpl`
* An item in keywords list: `-k%k`
* An item in input files list: `-%f`
* Source code charset: `--from-code=%c`

#### JavaScript
* Language: `JavaScript`
* Parser command: `php.exe -f "/path/to/GettextParser/index.php" JavaScript %o %C %K %F`
* List of extensions: `*.js`
* An item in keywords list: `-k%k`
* An item in input files list: `-%f`
* Source code charset: `--from-code=%c`

## Known issues
* plurals are not supported for Smarty (planned)
* works only on Windows (via xgettext.exe)

## Contributing
Contribution is highly encouraged! Just send your pull-requests or create issues.

## Tests
Library is covered with unit tests.
For running those tests you'll need [PHPUnit](https://github.com/sebastianbergmann/phpunit/).
It's recommended to use phpunit.phar.

Just specify `tests/phpunit.xml` as config file for PHPUnit:
```
php phpunit.phar -c tests/phpunit.xml
```

## License
This library is released under [MIT](http://www.tldrlegal.com/license/mit-license) license.