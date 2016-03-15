# Backpack\BackupManager

[![Latest Version on Packagist](https://img.shields.io/packagist/v/backpack/backupmanager.svg?style=flat-square)](https://packagist.org/packages/backpack/backupmanager)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/backpack/backupmanager/master.svg?style=flat-square)](https://travis-ci.org/backpack/backupmanager)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/backpack/backupmanager.svg?style=flat-square)](https://scrutinizer-ci.com/g/backpack/backupmanager/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/backpack/backupmanager.svg?style=flat-square)](https://scrutinizer-ci.com/g/backpack/backupmanager)
[![Total Downloads](https://img.shields.io/packagist/dt/backpack/backupmanager.svg?style=flat-square)](https://packagist.org/packages/backpack/backupmanager)

An admin interface for managing backups (download and delete). Used in the Backpack package, on Laravel 5.2+

## Install

1) In your terminal

``` bash
$ composer require backpack/backupmanager
```

2) Then add the service providers to your config/app.php file:

``` 
'Spatie\Backup\BackupServiceProvider',
'Backpack\BackupManager\BackupManagerServiceProvider',
```

3) Publish the config file and lang files:

```
php artisan vendor:publish --provider="Backpack\BackupManager\BackupManagerServiceProvider"
```

4) Add a new "disk" to config/filesystems.php:

```php
        // used for Backpack/BackupManager
        'backups' => [
            'driver' => 'local',
            'root'   => storage_path('backups'), // that's where your backups are stored by default: storage/backups
        ],
```
This is where you choose a different driver if you want your backups to be stored somewhere else (S3, Dropbox, Google Drive, Box, etc).

5) [optional] Add a menu item for it in resources/views/vendor/backpack/base/inc/sidebar.blade.php or menu.blade.php:

```html
<li><a href="{{ url('admin/backupmanager') }}"><i class="fa fa-hdd-o"></i> <span>Logs</span></a></li>
```

6) [optional] Modify your backup options in config/laravel-backup.php

## Usage

Point and click, baby. Point and click.

Try at **your-project-domain/admin/backup**

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
