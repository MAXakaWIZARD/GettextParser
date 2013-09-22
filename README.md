# gettext parser for poEdit

## Supported formats:
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

## Known issues:
* plurals are not supported for Smarty (planned)
* works only on Windows (via xgettext.exe)

## Usage (Smarty parser example):
Create new parser: `File->Preferences->Parsers->New`
Enter such params:

* Language: `Smarty`
* Parser command: `php.exe -f "/path/to/GettextParser/index.php" Smarty %o %C %K %F`
* List of extensions: `*.tpl`
* An item in keywords list: `-k%k`
* An item in input files list: `-%f`
* Source code charset: `--from-code=%c`