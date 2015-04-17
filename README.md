# gettext parser for Poedit
[![Build Status](https://api.travis-ci.org/MAXakaWIZARD/GettextParser.png?branch=master)](https://travis-ci.org/MAXakaWIZARD/GettextParser) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/MAXakaWIZARD/GettextParser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/MAXakaWIZARD/GettextParser/?branch=master)
[![Coverage Status](https://coveralls.io/repos/MAXakaWIZARD/GettextParser/badge.svg?branch=master)](https://coveralls.io/r/MAXakaWIZARD/GettextParser?branch=master)
[![Latest Stable Version](https://poser.pugx.org/maxakawizard/gettext-parser/v/stable.svg)](https://packagist.org/packages/maxakawizard/gettext-parser) 
[![Total Downloads](https://poser.pugx.org/maxakawizard/gettext-parser/downloads.svg)](https://packagist.org/packages/maxakawizard/gettext-parser) 
[![License](https://poser.pugx.org/maxakawizard/gettext-parser/license.svg)](https://packagist.org/packages/maxakawizard/gettext-parser)

Library for syncing gettext catalogs with Smarty and Javascript sources.

This package is compliant with [PSR-4](http://www.php-fig.org/psr/4/), [PSR-1](http://www.php-fig.org/psr/1/), and [PSR-2](http://www.php-fig.org/psr/2/).
If you notice compliance oversights, please send a patch via pull request.

## Installation
* Download sources and unpack to any folder.
* If needed, create `config.php` file (see `config.php.dist`) and specify path to `xgettext` binary

## Supported formats
### JavaScript
[jQuery.gettext](https://github.com/jakob-stoeck/jquery-gettext) plugin:
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
* An item in input files list: `%f`
* Source code charset: `--from-code=%c`

#### JavaScript
* Language: `JavaScript`
* Parser command: `php.exe -f "/path/to/GettextParser/index.php" JavaScript %o %C %K %F`
* List of extensions: `*.js`
* An item in keywords list: `-k%k`
* An item in input files list: `%f`
* Source code charset: `--from-code=%c`

## Known issues
* plurals are not supported for Smarty (planned)

## Contributing
Contribution is highly encouraged! Just send your pull-requests or create issues.

## Tests
Library is covered with unit tests.
For running those tests you'll need [PHPUnit](https://github.com/sebastianbergmann/phpunit/).
It's recommended to use `phpunit.phar`.

Just specify `tests/phpunit.xml.dist` as config file for PHPUnit (actually it can found it automatically):
```bash
php phpunit.phar -c tests/phpunit.xml.dist
```

## License
This library is released under [MIT](http://www.tldrlegal.com/license/mit-license) license.