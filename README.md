# TheGe's DevAid

Developer aid toolbox for PrestaShop 9.1+.

## Requirements

| Dependency  | Version              |
|-------------|----------------------|
| PHP         | 8.2 – 8.5            |
| PrestaShop  | 9.1.0 – 9.99.99      |

## Installation

1. Copy the `thegedevaid` folder into `<shop>/modules/`.
2. In the back office, go to **Modules → Module Manager**.
3. Search for **Development Aids** and click **Install**.
4. Open **Configure** to toggle the module's `Active` flag.

A Composer autoloader is optional — the module ships a PSR-4 fallback
autoloader inside `thegedevaid.php`. Run `composer install` only if you
intend to add third-party dependencies.

## Architecture

```
thegedevaid
   ├── .dev                                  # development tooling
   │   ├── bin
   │   │   ├── find-unused-files
   │   │   ├── find-unused-files.php
   │   │   ├── performance-test
   │   │   ├── performance-test.php
   │   │   ├── php-versions
   │   │   └── z
   │   ├── config
   │   ├── src
   │   │   └── Utils
   │   │       └── UnusedFilesFinder.php
   │   ├── tests
   │   │   ├── integration
   │   │   └── unit
   │   ├── var
   │   ├── .env
   │   └── composer.json
   ├── config
   │   ├── admin
   │   │   └── services.yml                  # service definitions
   │   ├── index.php
   │   └── routes.yml
   ├── data
   │   ├── fixes.json
   │   └── install.sql
   ├── docs
   ├── src
   │   ├── Configuration                     # configuration data and form
   │   │   ├── ConfigurationController.php
   │   │   ├── ConfigurationDataManager.php
   │   │   ├── ConfigurationDataProvider.php
   │   │   ├── ConfigurationFormType.php
   │   │   └── ConfigurationSpec.php
   │   ├── Contract
   │   │   └── ModuleCore.php
   │   ├── Exception                         # custom exceptions
   │   │   ├── ConfigurationException.php
   │   │   └── InvalidHookException.php
   │   ├── Hook
   │   │   └── HookActionAdminControllerSetMedia.php
   │   ├── Utils
   │   │   ├── CoreUtils.php
   │   │   ├── StylesheetArgsInAdmin.php
   │   │   └── StylesheetArgsInFrontend.php
   │   └── Core.php                          # namespaced module (all logic)
   ├── translations
   │   └── index.php
   ├── vendor
   │   ├── composer
   │   │   ├── ClassLoader.php
   │   │   ├── InstalledVersions.php
   │   │   ├── LICENSE
   │   │   ├── autoload_classmap.php
   │   │   ├── autoload_namespaces.php
   │   │   ├── autoload_psr4.php
   │   │   ├── autoload_real.php
   │   │   ├── autoload_static.php
   │   │   ├── installed.json
   │   │   ├── installed.php
   │   │   └── platform_check.php
   │   └── autoload.php
   ├── views
   │   ├── PrestaShop
   │   │   └── Admin
   │   │       └── Module
   │   │           └── Includes
   │   │               └── card_list.html.twig
   │   ├── css
   │   │   └── admin.css
   │   └── templates
   │       └── admin
   │           └── configuration.html.twig
   ├── composer.json
   ├── composer.lock
   └── thegedevaid.php                       # thin bootstrap + legacy class
```

## Configuration

| Key                  | Type   | Default | Description                       |
|----------------------|--------|---------|-----------------------------------|
| `THEGEDEVAID_ACTIVE` | `bool` | `false` | Master on/off switch for the aid. |

## Translations

The module declares the `Modules.Thegedevaid.Admin` translation domain and
uses PrestaShop's new translation system. Drop `.xlf` catalogs into
`translations/<locale>/` to localise the back-office labels.

## License

AFL 3.0
