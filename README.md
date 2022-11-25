# Laravel autodiscovery lock

This package allows you to monitor the [autodiscovery of your Laravel application](https://github.com/goedemiddag/laravel-autodiscovery-lock). By running the supplied command in the 
post-autoload-dump event, you can generate a file that contains all the autodiscovered packages.  This file can then be used to check for unacknowledged autodiscoveries.

## Installation
First use composer to install the package using the following command
```bash 
composer require goedemiddag/laravel-autodiscovery-lock
```

This will register a command in your application. To use this command, you need to add it to the post-autoload-dump event in your composer.json file right under the `package:discover` function laravel has already registered there.
It should look something like this:
```json
{
    "scripts": {
        "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover --ansi",
            "@php artisan autodiscovery:generate-lock"
        ]
    }
}
```

## Usage
To generate the first autodiscovery lock file, run the following command:
```bash
php artisan autodiscovery:generate-lock
```

This will generate a file called `autodiscovery.lock` in the root of your project. This file contains all the autodiscovered packages and which providers / aliases they provide.
After generating and committing this file, and registering the above command in the composer.json scripts you should be set for automatic updates each time composer installs or updates a package,
or dumps the autoload in general. 


## Pipeline examples
TBD
