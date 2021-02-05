<?php
defined('TYPO3') or die();

(function () {
    // Register upgrade wizard
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['fluidcontentToFluxMigration']
        = \DifferentTechnology\FluidcontentFluxMigration\Updates\FluidcontentToFluxMigrationUpdate::class;
})();
