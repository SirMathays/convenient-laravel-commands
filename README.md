# Convenient Laravel Commands

This package provides some additional, convenient commands for you to use with your Laravel project.

## Installation

Install the package with Composer:

    composer require sirmathays/convenient-laravel-commands

## Commands

This package provides following artisan make commands.

Commands for creating PHP OOP types:

    artisan make:class --type[=TYPE] <name>

Valid type options: final, abstract.

    artisan make:interface <name>
    artisan make:trait <name>

Commands for Model development:

    artisan make:concern <name>

Glorified trait following Laravel naming conventions. Defaults to the folder Models exist in.

    artisan make:relationship --explicit --model[=MODEL] --relation[=RELATION] <name>

For relationship traits. Command parses the given command name and tries to resolve both the relation and the model class. Both can be overwritten by providing options for each. By adding explicit option, parsing will not be used and options for both model and relation must be provided.

Commands for creating classes for Spatie's QueryBuilder _(if part of the project)_:

    artisan make:query-filter <name>
    artisan make:query-sort <name>

## License

Convenient Laravel Commands is open-sourced software licensed under the [MIT license](LICENSE.md).
