<?php
declare(strict_types = 1);
namespace DifferentTechnology\FluidcontentFluxMigration\Updates;

/*
 * This file is part of TYPO3 CMS-extension fluidcontent_flux_migration by different.technology
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Command for migrating fields from "pages.tx_realurl_exclude"
 * into "pages.exclude_slug_for_subpages".
 */
class FluidcontentToFluxMigrationUpdate implements UpgradeWizardInterface
{
    public function getIdentifier(): string
    {
        return 'fluidcontentToFluxMigration';
    }

    public function getTitle(): string
    {
        return 'Fluidcontent to Flux migration';
    }

    public function getDescription(): string
    {
        return 'This updater migrates old fluidcontent elements to flux elements';
    }

    public function executeUpdate(): bool
    {
        $elementsToUpdate = $this->getElementsToUpdate();
        foreach ($elementsToUpdate as $element) {
            // element = Vendor.MyExtension:Button.html
            // or element = MyExtension:Button.html
            $contentTypeParts = explode(':', $element['tx_fed_fcefile'], 2);
            $file = str_replace('.html', '', strtolower($contentTypeParts[1]));
            $vendorParts = explode('.', $contentTypeParts[0], 2);
            if (count($vendorParts) === 1) {
                $vendor = $vendorParts[0];
            } else {
                $vendor = $vendorParts[1];
            }
            $vendor = strtolower(str_replace('_', '', $vendor));
            $newContentType = $vendor . '_' . $file;

            // update entries of this content type
            $queryBuilder = $this->getContentQueryBuilder();
            $queryBuilder->update('tt_content')->where(
                $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('fluidcontent_content')),
                $queryBuilder->expr()->eq('tx_fed_fcefile', $queryBuilder->createNamedParameter($element['tx_fed_fcefile'])),
            );
            $queryBuilder->set('CType', $newContentType, true);
            $queryBuilder->execute();
        }
        return true;
    }

    public function updateNecessary(): bool
    {
        return count($this->getElementsToUpdate()) > 0;
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }

    protected function getElementsToUpdate(): array
    {
        $queryBuilder = $this->getContentQueryBuilder();
        $statement = $queryBuilder->select('tx_fed_fcefile')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('fluidcontent_content')),
                $queryBuilder->expr()->neq('tx_fed_fcefile', $queryBuilder->createNamedParameter('')),
            )
            ->groupBy('tx_fed_fcefile')
            ->execute();
        return $statement->fetchAllAssociative();
    }

    protected function getContentQueryBuilder(): QueryBuilder
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll();
        return $queryBuilder;
    }
}
