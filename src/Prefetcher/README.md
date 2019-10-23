<h1 align="center">Narrowspark Automatic</h1>
<p align="center">
    <a href="https://github.com/narrowspark/automatic/releases"><img src="https://img.shields.io/packagist/v/narrowspark/automatic.svg?style=flat-square"></a>
    <a href="https://php.net/"><img src="https://img.shields.io/badge/php-%5E7.1.0-8892BF.svg?style=flat-square"></a>
    <a href="https://codecov.io/gh/narrowspark/automatic"><img src="https://img.shields.io/codecov/c/github/narrowspark/automatic/master.svg?style=flat-square"></a>
    <a href="#"><img src="https://img.shields.io/badge/style-level%207-brightgreen.svg?style=flat-square&label=phpstan"></a>
    <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square"></a>
</p>

Branch Status
------------
[![Travis branch](https://img.shields.io/travis/narrowspark/automatic/master.svg?longCache=false&style=for-the-badge)](https://travis-ci.org/narrowspark/automatic)
[![Appveyor branch](https://img.shields.io/appveyor/ci/narrowspark/automatic/master.svg?longCache=false&style=for-the-badge)](https://ci.appveyor.com/project/narrowspark/automatic/branch/master)

> **Note** This package is part of the [Narrowspark automatic](https://github.com/narrowspark/automatic). 

Installation
-------------

Use [Composer](https://getcomposer.org/) to install this package:

```sh
composer global require narrowspark/automatic-composer-prefetcher --dev
```

Usage
-------------

The prefetcher will be executed when `composer require` , `composer install` or `composer update`
is used, you will experience a speed up of composer package installations.

Narrowspark Automatic Prefetcher supports on `skipping legacy package tags`.

There are two ways to skip old tags of a package.

The first one is to use the `composer.json extra` field, just add `prefetcher` inside of this a `require` key,
then you packages with the version you want start skipping.

```json5
{
    "extra": {
        "prefetcher": {
            "require": {
                "symfony/symfony": "4.2.*",
                "next package": "1.*"
            }
        }
    }
}
```

And the second one is to use the global `env` variable

```bash
export AUTOMATIC_PREFETCHER_REQUIRE="symfony/symfony:4.2.*[, and you next package]"
```
Contributing
------------

Issues for this package shall be posted on [Narrowspark Automatic issues](https://github.com/narrowspark/automatic/issues). <br>
Thank you for considering contributing to the Narrowspark automatic.

> **Note** Please note that this project is released with a Contributor Code of Conduct. By participating in this project you agree to abide by its terms.

Credits
-------------

- [Daniel Bannert](https://github.com/prisis)
- [All Contributors](https://github.com/narrowspark/automatic/graphs/contributors)
- Narrowspark Automatic Prefetcher has been inspired by [symfony/flex](https://github.com/symfony/flex)

License
-------------

The MIT License (MIT). Please see [License File](LICENSE) for more information.