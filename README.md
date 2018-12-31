# Smartthings Provider for OAuth 2.0 Client

[![Latest Version](https://img.shields.io/github/release/badpirate/oauth2-smartthings.svg?style=flat-square)](https://github.com/badpirate/oauth2-smartthings/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/badpirate/oauth2-smartthings/master.svg?style=flat-square)](https://travis-ci.org/badpirate/oauth2-smartthings)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/badpirate/oauth2-smartthings.svg?style=flat-square)](https://scrutinizer-ci.com/g/badpirate/oauth2-smartthings/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/badpirate/oauth2-smartthings.svg?style=flat-square)](https://scrutinizer-ci.com/g/badpirate/oauth2-smartthings)
[![Total Downloads](https://img.shields.io/packagist/dt/badpirate/oauth2-smartthings.svg?style=flat-square)](https://packagist.org/packages/badpirate/oauth2-smartthings)

This package provides Smartthings OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require badpirate/oauth2-smartthings
```

## Usage

Usage is the same as The League's OAuth client, using `\Badpirate\OAuth2\Client\Provider\Smartthings` as the provider.

### Authorization Code Flow

```php
$provider = new BadPirate\OAuth2\Client\Provider\SmartThings([
    'clientId'          => '{smartthings-client-id}',
    'clientSecret'      => '{smartthings-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url'
]);
```
For further usage of this package please refer to the [core package documentation on "Authorization Code Grant"](https://github.com/thephpleague/oauth2-client#usage).

### Resource owner information

Smartthings does not support access to any personal information of the authorizing resource owner. As such, this package does not support the `getResourceOwner` method documented in the core package.

This package will throw a `BadPirate\OAuth2\Client\Provider\Exception\ResourceOwnerException` exception if you attempt to use this method.

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/badpirate/oauth2-smartthings/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Kevin Lohman](https://github.com/badpirate)
- [All Contributors](https://github.com/badpirate/oauth2-smartthings/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/badpirate/oauth2-smartthings/blob/master/LICENSE) for more information.
