[![Packagist Release](https://img.shields.io/packagist/v/different-technology/fluidcontent-flux-migration.svg?style=flat-square)](https://packagist.org/packages/different-technology/fluidcontent-flux-migration)
[![Packagist Downloads](https://img.shields.io/packagist/dt/different-technology/fluidcontent-flux-migration.svg?style=flat-square)](https://packagist.org/packages/different-technology/fluidcontent-flux-migration)
[![GitHub License](https://img.shields.io/github/license/different-technology/fluidcontent_flux_migration.svg?style=flat-square)](https://github.com/different-technology/fluidcontent_flux_migration/blob/main/LICENSE)

# Fluid Content to Flux migration

This TYPO3 extension adds an upgrade wizard to your TYPO3 instance which converts fluidcontent elements to flux elements.

Please do not forget to run the flux update script in the extension manager after executing this migration.

## Setup

Install the extension:
```bash
composer require different-technology/fluidcontent-flux-migration
```

## Migrate your content

1. Run the upgrade wizard with the install tool or with the CLI
```bash
bin/typo3 upgrade:run fluidcontentToFluxMigration
```

2. Go to the Extension Manager your TYPO3 backend and execute the update script of the `flux` extension

3. (optional) Feel free to remove this extension after your content is migrated
```bash
composer remove different-technology/fluidcontent-flux-migration
```
