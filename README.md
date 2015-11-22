# JSONx

[![](https://api.travis-ci.org/danharper/JSONx.svg)](https://travis-ci.org/danharper/JSONx) [![Latest Stable Version](https://poser.pugx.org/danharper/jsonx/v/stable)](https://packagist.org/packages/danharper/jsonx) [![License](https://poser.pugx.org/danharper/jsonx/license)](https://packagist.org/packages/danharper/jsonx)

A library implementing IBM's standard format for [representing JSON as XML](http://www-01.ibm.com/support/knowledgecenter/SS9H2Y_6.0.0/com.ibm.dp.xm.doc/json_jsonx.html). Provides support for both reading & writing of JSONx. [The spec draft for JSONx can be found here](http://tools.ietf.org/html/draft-rsalz-jsonx-00).

**WHY?!** That was my initial reaction, too. However it's very useful when you're trying to integrate one system which speaks JSON with another which speaks XML, without having to make potentially large changes.

> **Notice**
> This library will hit v1.0 _soon_. It's heavily tested and the API is stable (two methods), the only thing which will change are the exceptions and any potential bugs or edge-cases I've missed.

## Installation

```
composer require danharper/jsonx
```

## Usage

```php
$jsonx = new danharper\JSONx\JSONx;

$jsonx->toJSONx([
  'foo' => [ 'bar', true, 2, 3.14, null, [], (object) [] ]
]);

/*
<?xml version="1.0" encoding="UTF-8"?>
<json:object xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx" xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <json:array name="foo">
    <json:string>bar</json:string>
    <json:boolean>true</json:boolean>
    <json:number>2</json:number>
    <json:number>3.14</json:number>
    <json:null/>
    <json:array/>
    <json:object/>
  </json:array>
</json:object>
*/

$jsonx->fromJSONx('
<?xml version="1.0" encoding="UTF-8"?>
<json:object xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx" xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <json:array name="foo">
    <json:string>bar</json:string>
    <json:boolean>true</json:boolean>
    <json:number>2</json:number>
    <json:number>3.14</json:number>
    <json:null/>
    <json:array/>
    <json:object/>
  </json:array>
</json:object>
');

/*
object(stdClass)#180 (1) {
  ["foo"]=> array(7) {
    [0]=> string(3) "bar"
    [1]=> bool(true)
    [2]=> int(2)
    [3]=> float(3.14)
    [4]=> NULL
    [5]=> array(0) {
    }
    [6]=> object(stdClass)#173 (0) {
    }
  }
}
*/
```

## Integrations

Want to add full XML support to your JSON-speaking Laravel API with just one middleware? Check out [`danharper/laravel-jsonx`](https://github.com/danharper/LaravelJSONx).

Want automatic conversion of your PSR-7 compatible messages? Check out [`danharper/psr7-jsonx`](https://github.com/danharper/Psr7JSONx).
