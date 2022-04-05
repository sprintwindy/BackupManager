# Backpack\BackupManager

[![Latest Version on Packagist](https://img.shields.io/packagist/v/backpack/backupmanager.svg?style=flat-square)](https://packagist.org/packages/backpack/backupmanager)
[![Software License](https://img.shields.io/badge/license-dual-blue?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-backpack/backupmanager/master.svg?style=flat-square)](https://travis-ci.org/laravel-backpack/backupmanager)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/laravel-backpack/backupmanager.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-backpack/backupmanager/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-backpack/backupmanager.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-backpack/backupmanager)
[![Style CI](https://styleci.io/repos/53956594/shield)](https://styleci.io/repos/53956594)
[![Total Downloads](https://img.shields.io/packagist/dt/backpack/backupmanager.svg?style=flat-square)](https://packagist.org/packages/backpack/backupmanager)

An admin interface for [spatie/laravel-backup](https://github.com/spatie/laravel-backup). Allows the admin to easily manage backups (download and delete). Used in the Backpack package, on Laravel 5.2+ to 9.


> ### Security updates and breaking changes
> Please **[subscribe to the Backpack Newsletter](http://backpackforlaravel.com/newsletter)** so you can find out about any security updates, breaking changes or major features. We send an email 2 times/year, max.

![Backpack\BackupManager screenshot](https://user-images.githubusercontent.com/1032474/150080754-97dca93f-3cac-452b-9bcf-cc51becd3055.png)


## Install

1) In your terminal

``` bash
# Install the package
composer require backpack/backupmanager

# Publish the backup and backupmanager configs and lang files:
php artisan vendor:publish --provider="Backpack\BackupManager\BackupManagerServiceProvider" --tag=backup-config --tag=lang

# [optional] Add a sidebar_content item for it
php artisan backpack:add-sidebar-content "<li class='nav-item'><a class='nav-link' href='{{ backpack_url('backup') }}'><i class='nav-icon la la-hdd-o'></i> Backups</a></li>"
```

If you need to configure aditional stuff to your backup process like notifications you should publish the spatie backup file `php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider" --tag="backup-config"` and check [spatie documentation on how to configure your backup system](https://spatie.be/docs/laravel-backup/v8/installation-and-setup) in `config/backup.php`

As far as `config/backpack/backupmanager.php` it configures how the `Backup` button works: `backup:run --disable-notifications` by default, [check backup documentation for aditional flags](https://spatie.be/docs/laravel-backup/v8/taking-backups/overview). By default notifications are disabled with `--disable-notifications` flag since they need aditional user configuration, **but is higly recommended that you configure them**. [Check here the docs on how to do it](https://spatie.be/docs/laravel-backup/v8/sending-notifications/overview), then remove the flag `--disable-notifications` from `config/backpack/backupmanager.php` button script.

**[TIP]** When you modify your options in `config/backup.php` or `config/backpack/backupmanager.php`, please run manually `php artisan backup:run` to make sure it's still working after your changes. **NOTE**: `php artisan optimize:clear` and/or `php artisan config:clear` might be needed before the `backup:run` command.

5) [optional] Instruct Laravel to run the backups automatically in your console kernel:

```php
// app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
    // if you are not using notifications you should add the `--disable-notifications` flag to this commands
    $schedule->command('backup:clean')->daily()->at('04:00');
    $schedule->command('backup:run')->daily()->at('05:00');
}
```

6) Check that it works

If the "unknown error" yellow bubble is thrown and you see the "_Backup failed because The dump process failed with exitcode 127 : Command not found._" error in the log file, either mysqldump / pg_dump is not installed or you need to specify its location.

You can do that in your `config/database.php` file, where you define your database credentials, by adding the dump variables. Example for Mac OS X's MAMP:

```php
'mysql' => [
    'driver'            => 'mysql',
    'host'              => env('DB_HOST', 'localhost'),
    'database'          => env('DB_DATABASE', 'forge'),
    'username'          => env('DB_USERNAME', 'forge'),
    'password'          => env('DB_PASSWORD', ''),
    'charset'           => 'utf8',
    'collation'         => 'utf8_unicode_ci',
    'prefix'            => '',
    'strict'            => false,
    'engine'            => null,
    'dump' => [

        'dump_binary_path' => '/path/to/directory/', // only the path, without `mysqldump` or `pg_dump`
        // 'dump_binary_path' => '/Applications/MAMP/Library/bin/', // works for MAMP on Mac OS
        // 'dump_binary_path' => '/opt/homebrew/bin/', // works for Laravel Valet on Mac OS
        'use_single_transaction',
        'timeout' => 60 * 5, // 5 minute timeout
        // 'exclude_tables' => ['table1', 'table2'],
        // 'add_extra_option' => '--optionname=optionvalue',
    ]
],
```

## Usage

This should be a point-and-click interface where you can create and download backups at any time.

Try at **your-project-domain/admin/backup**

## Upgrading from 3.x to 4.x

Change your required version to `"backpack/backupmanager": "^4.0"` and run `composer update backpack/backupmanager`.

We removed the overrides of spatie config from our package publishing process, from now on you can do the regular spatie backup configuration in `config/backup.php` and the customized backpack configuration in `config/backpack/backupmanager`.

**1)** Publish the new config file `php artisan vendor:publish --provider="Backpack\BackupManager\BackupManagerServiceProvider" --tag="backup-config"`. This will generate the `config/backpack/backupmanager.php` file. By default backpack uses `--disable-notifications` flag, remove it if you are using notifications.

**2)** If you have configured `backpack_flags` in `config/backup` you should now move them to the new config, under the key: `artisan_command_on_button_click`. 

```php
// This command will be run when user click on the "Create a new backup" button
// You can add flags to this like --only-db --only-files --only-to-disk=name-of-disk --disable-notifications
// Details here: https://spatie.be/docs/laravel-backup/v8/taking-backups/overview
'artisan_command_on_button_click' => 'backup:run --disable-notifications',
```

**3)** If you didn't do anymore configs you can now safely remove the `config/backup.php` file and there is no need to re-publish the spatie config, **jump to step 5**.

**4)** If you are customizing other options in `config/backup.php` file make sure that your changes are compatible with the new config (it should be), otherwise save your config file in some other place, force publish the v8 spatie configuration file with `php artisan vendor:publish --force --provider="Spatie\Backup\BackupServiceProvider" --tag="backup-config"` and then re-configure what you need. 

**5)** You may need to clear the cache with `php artisan optimize:clear` and/or `php artisan config:clear`.

**6)** Manually run from console the `backup:run` command to make sure it's working, use `backup:run --disable-notifications` if you are not using notifications.

**7)** If you are **scheduling your backup jobs** make sure to also use the apropriate synthax in the commands. In previous backup versions the notification exceptions would **not be reported** and your scripts would run fine even if an exception was thrown. Now **they report**, so to avoid the exceptions halting your scripts in the notification part, **you need to explicitly tell the backup script that you don't want notifications** if you didn't configure them:

```php
// app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
    // this would work previously even if you didn't configured notifications, 
    // it would throw an exception but it was not reported, so script is not halted.
    $schedule->command('backup:clean')->daily()->at('04:00');

    // now if you don't use notifications you should explicitly tell that to backup
    // otherwise the same exception will be thrown, but this time reported, 
    // halting the script execution.
    $schedule->command('backup:clean --disable-notifications')->daily()->at('04:00');
}
```

## Upgrading from 2.x to 3.x

Change your required version to ```"backpack/backupmanager": "^3.0",``` and run ```composer update```. There are no breaking changes just icons that are show using ```la la-icon``` instead of ```fa fa-icon```.


## Upgrading from 1.2.x to 1.3.x

1) change your required version to ```"backpack/backupmanager": "^1.3",``` and run ```composer update```;
2) delete the old config file (too many changes, including namechange): ```rm config/laravel-backup.php```
3) republish the config files: ```php artisan vendor:publish --provider="Backpack\BackupManager\BackupManagerServiceProvider"```
4) change your db configuration in ```config/database.php``` to use the new dump configuration (all options in one array; the example below is for MAMP on MacOS):

```php
'dump' => [
    'dump_binary_path' => '/Applications/MAMP/Library/bin/', // only the path, so without `mysqldump` or `pg_dump`
    'use_single_transaction',
    'timeout' => 60 * 5, // 5 minute timeout
    // 'exclude_tables' => ['table1', 'table2'],
    // 'add_extra_option' => '--optionname=optionvalue',
]
```
5) Create a backup in the interface to test it works. If it doesn't try ```php artisan backup:run``` to see what the problem is.


## Upgrading from 1.1.x to 1.2.x

1) change your required version to ```"backpack/backupmanager": "^1.2",```;
2) the only breaking change is that the ```config/database.php``` dump variables are now inside an array. Please see the step 8 above, copy-paste the ```dump``` array from there and customize;


## Change log

Please see the [releases page](https://github.com/Laravel-Backpack/BackupManager/releases/) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Overwriting Functionality

If you need to modify how this works in a project: 
- create a ```routes/backpack/backupmanager.php``` file; the package will see that, and load _your_ routes file, instead of the one in the package; 
- create controllers/models that extend the ones in the package, and use those in your new routes file;
- modify anything you'd like in the new controllers/models;

## Security

If you discover any security related issues, please email tabacitu@backpackforlaravel.com instead of using the issue tracker.

Please **[subscribe to the Backpack Newsletter](http://backpackforlaravel.com/newsletter)** so you can find out about any security updates, breaking changes or major features. We send an email every 1-2 months.

## Credits

- [Cristian Tabacitu](https://github.com/tabacitu)
- [All Contributors](../../contributors)

## License

Backpack is free for non-commercial use and 49 EUR/project for commercial use. Please see [License File](LICENSE.md) and [backpackforlaravel.com](https://backpackforlaravel.com/#pricing) for more information.

## Hire us

We've spend more than 10.000 hours creating, polishing and maintaining administration panels on Laravel. We've developed e-Commerce, e-Learning, ERPs, social networks, payment gateways and much more. We've worked on admin panels _so much_, that we've created one of the most popular software in its niche - just from making public what was repetitive in our projects.

If you are looking for a developer/team to help you build an admin panel on Laravel, look no further. You'll have a difficult time finding someone with more experience & enthusiasm for this. This is _what we do_. [Contact us - let's see if we can work together](https://backpackforlaravel.com/need-freelancer-or-development-team).
