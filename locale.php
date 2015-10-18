<?php
/*
The MIT License (MIT)

Copyright (c) 2015 BlackCetha

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
class localization {
    private $localeCache;
    private $fallbackCache;
    private $lang;
    function __construct ($lang = null) {
        $data = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR."localization.json"), true);
        if ($lang == null)
            $lang = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
        foreach($data as $k => $v) {
            if ($k == $lang) {
                $exists = true;
                break;
            }
        }
        if (!$exists)
            $lang = "en";
        foreach ($data[$lang] as $k => $v)
            $this->localeCache[$k] = $v;
        if ("lang" != "en") {
            foreach ($data["en"] as $k => $v) {
                $this->fallbackCache[$k] = $v;
            }
        }
        $this->lang = $lang;
        echo $this->lang." - ".$lang;
    }
    function __invoke ($name) {
        if (isset($this->localeCache[$name]))
            return $this->localeCache[$name];
        if (isset($this->fallbackCache[$name]))
            return $this->fallbackCache[$name];
        return "[TRANSLATION MISSING]";
    }
    function addLocalization ($name, $localization, $lang = "") {
        if (empty($name) || empty($localization)) return false;
        if ($lang == "") $lang = $this->lang;
        $data = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR."localization.json"), true);
        $data[$lang][$name] = $localization;
        $file = fopen(__DIR__.DIRECTORY_SEPARATOR."localization.json","w");
        $result = fwrite($file, json_encode($data));
        fclose($file);
        if ($lang == $this->lang)
            $this->localeCache[$name] = $localization;
        if ($lang == "en")
            $this->fallbackCache[$name] = $localization;
        return (bool)$result;
    }
    function removeLocalization ($name, $lang = "") {
        if (empty($name)) return false;
        if ($lang == "") $lang = $this->lang;
        $data = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR."localization.json"), true);
        unset($data[$lang][$name]);
        $file = fopen(__DIR__.DIRECTORY_SEPARATOR."localization.json","w");
        $result = fwrite($file, json_encode($data));
        fclose($file);
        return (bool)$result;
    }
    function exists ($name, $lang = "") {
        if ($lang == "") $lang = $this->lang;
        if ($lang == $this->lang)
            return isset($this->localeCache[$name]);
        if ($lang == "en")
            return isset($this->fallbackCache[$name]);
        $data = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR."localization.json"), true);
        return isset($data[$lang][$name]);
    }
    function place ($name, $localization) {
        $this->localeCache[$name] = $localization;
    }
}