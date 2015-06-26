# backupmanager

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dick/backupmanager.svg?style=flat-square)](https://packagist.org/packages/dick/backupmanager)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/dick/backupmanager/master.svg?style=flat-square)](https://travis-ci.org/dick/backupmanager)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/dick/backupmanager.svg?style=flat-square)](https://scrutinizer-ci.com/g/dick/backupmanager/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/dick/backupmanager.svg?style=flat-square)](https://scrutinizer-ci.com/g/dick/backupmanager)
[![Total Downloads](https://img.shields.io/packagist/dt/dick/backupmanager.svg?style=flat-square)](https://packagist.org/packages/dick/backupmanager)

An admin interface for managing backups (download and delete). Used in the Dick Admin package, on Laravel 5.

## Install

Via Composer

``` bash
$ composer require dick/backupmanager
```

Then add the service providers to your config/app.php file:

``` 
'Spatie\Backup\BackupServiceProvider',
'Dick\BackupManager\BackupManagerServiceProvider',
```

Publish the config files:

```
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
php artisan vendor:publish --provider="Dick\BackupManager\BackupManagerServiceProvider"
```

## Usage

Add a menu element for it:

``` php
[
    'label' => "Backups",
    'route' => 'admin/backup',
    'icon' => 'fa fa-hdd-o',
],
```

Or just try at **your-project-domain/admin/backup**

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email hello@tabacitu.ro instead of using the issue tracker.

## Credits

- [Cristian Tabacitu](https://github.com/tabacitu)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
