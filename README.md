# Symfony Git Bundle


[![@jbtcdDE on Twitter](http://img.shields.io/badge/twitter-%40jbtcdDE-blue.svg?style=flat)](https://twitter.com/jbtcdDE)
[![Build Status](https://travis-ci.com/jbtcd/git-bundle.svg?branch=main)](https://travis-ci.com/jbtcd/git-bundle)
[![license](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![packagist](https://img.shields.io/packagist/v/jbtcd/git-bundle.svg?style=flat-square)](https://packagist.org/packages/jbtcd/git-bundle)
[![downloads](https://img.shields.io/packagist/dt/jbtcd/git-bundle.svg?style=flat-square)](https://packagist.org/packages/jbtcd/git-bundle)
[![php version](https://img.shields.io/packagist/php-v/jbtcd/git-bundle?style=flat-square)](https://packagist.org/packages/jbtcd/git-bundle)

## Installation

To install, use composer:

```bash
$ composer require jbtcd/git-bundle
```

## Testing

``` bash
$ composer run tests
```

## Note

This plugin makes Symfony significantly slower. Approximately 10 ms per commit to be read. By default, this means about 100 ms.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
