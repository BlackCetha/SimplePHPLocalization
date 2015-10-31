# SimplePHPLocalization

A simple but powerful class for localization in PHP.

#Usage

Include and initialize the class, with the shorthand ISO-name to set the language (for example "de").
If no language is set, the users preference will read from the browser.
Should the users language not have translation, English will be used as a fallback.
```php
require "locale.php";
$loc = new localization("de"); # Set german as language
$loc = new localization(); # Use user-preference
```
Translations are accessable by calling the class as a function.
```php
$loc("admin"); # --> Administrator
$loc("homepage") # --> Hauptseite (German)
```
English will be used as fallback if there is no translation.
```php
$loc("sloth"); # --> Not defined in german --> Sloth
$loc("homepage"); # --> Hauptseite (doesnt change main language)
```
It is possible to replace or add translations for once.
```php
$loc("homepage"); # --> Hauptseite
$loc->place("homepage", "Hauptpage");
$loc("homepage"); # --> Hauptpage # This is only changed for the rest of the script
```
AutoEcho mode can be toggled using
```php
$loc->setAutoEcho(true/false);
```

#Adding or changing translations

Translations are saved in the file "localization.json" in the same directory as the class.
The file follows this pattern:
```json
{
  "language shorthand": {
    "key": "translation"
  }
}
```

Translations can also be changed with theese functions.
They will change the localization for the running script aswell as in the localization file.
```php
$loc->addLocalization($key, $translation, $language (defaults to current));
# This will also override existing translations
$loc->removeLocalization($key, $lang (defaults to current));
```

Check for the existence of a translation with
```php
$loc->exists($key, $checkInFallback (defaults to false));
```
