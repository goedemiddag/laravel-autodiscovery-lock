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
The package has two main usages. Generating a lock file to keep track of the autodiscoveries and checking for unacknowledged autodiscoveries,   
and Verifying that your lock file and autodiscovered packages are in sync.
### Generating the lock file
To generate the first autodiscovery lock file, run the following command:
```bash
php artisan autodiscovery:generate-lock
```

This will generate a file called `autodiscovery.lock` in the root of your project. This file contains all the autodiscovered packages and which providers / aliases they provide.
After generating and committing this file, and registering the above command in the composer.json scripts you should be set for automatic updates each time composer installs or updates a package,
or dumps the autoload in general. 


### Verifying the lock file
Validating the lock file allows you to make sure installed packages did not change their autodiscoveries without you knowing. To validate the lock file, run the following command:
```bash
php artisan autodiscovery:verify-lock
```
If there is a difference between the lock and the autodiscovered packages, the command will exit with a non-zero exit code. This allows you to use this command in your CI/CD pipeline with ease.
Just run the command in the pipeline for your Pull Requests, and/or run the command in your build pipeline.

## Pipeline examples
TBD

## Contributing

Found a bug or want to add a new feature? Great! There are also many other ways to make meaningful contributions such as reviewing outstanding pull requests and writing documentation. Even opening an issue for a bug you found is appreciated.

If you would like to contribute code, please see our [contributing guide](CONTRIBUTING.md)

## Credits 
- [Bonroyage](https://github.com/bonroyage) - For helping out with the initial implementation.
- [NiekKeijzer](https://github.com/NiekKeijzer) - For reviewing the code for the initial implementation.
- [All Contributors](https://github.com/goedemiddag/laravel-autodiscovery-lock/graphs/contributors)