<?php

declare(strict_types=1);

namespace B13\Container\Tests\Acceptance\Support\Extension;

/*
 * This file is part of TYPO3 CMS-based extension "container" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use Codeception\Event\SuiteEvent;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Acceptance\Extension\BackendEnvironment;
use TYPO3\TestingFramework\Core\Testbase;

class BackendContainerEnvironment extends BackendEnvironment
{
    /**
     * @var array
     */
    protected $localConfig = [
        'coreExtensionsToLoad' => [
            'core',
            'extbase',
            'fluid',
            'backend',
            'install',
            'frontend',
            'recordlist',
            'workspaces',
            'info',
        ],
        'pathsToLinkInTestInstance' => [
            'typo3conf/ext/container/Build/sites' => 'typo3conf/sites',
        ],
        'testExtensionsToLoad' => [
            'typo3conf/ext/container',
            'typo3conf/ext/container_example',
            'typo3conf/ext/content_defender',
        ],
        'csvDatabaseFixtures' => [
            __DIR__ . '/../../Fixtures/be_users.csv',
            __DIR__ . '/../../Fixtures/contentDefenderMaxitems.csv',
            __DIR__ . '/../../Fixtures/contentTCASelectCtype.csv',
            __DIR__ . '/../../Fixtures/emptyPage.csv',
            __DIR__ . '/../../Fixtures/pageWithContainer.csv',
            __DIR__ . '/../../Fixtures/pageWithContainer-2.csv',
            __DIR__ . '/../../Fixtures/pageWithDifferentContainers.csv',
            __DIR__ . '/../../Fixtures/pageWithLocalization.csv',
            __DIR__ . '/../../Fixtures/pageWithLocalizationFreeModeWithContainer.csv',
            __DIR__ . '/../../Fixtures/pageWithTranslatedContainer.csv',
            __DIR__ . '/../../Fixtures/pageWithTranslatedContainer-2.csv',
            __DIR__ . '/../../Fixtures/pageWithContainer-3.csv',
            __DIR__ . '/../../Fixtures/pageWithContainer-4.csv',
            __DIR__ . '/../../Fixtures/pageWithContainer-5.csv',
            __DIR__ . '/../../Fixtures/pageWithContainer-6.csv',
            __DIR__ . '/../../Fixtures/pageWithWorkspace.csv',
            __DIR__ . '/../../Fixtures/pageWithWorkspace-movedContainer.csv',
            __DIR__ . '/../../Fixtures/pageWithContainerAndContentElementOutside.csv',
            __DIR__ . '/../../Fixtures/pages.csv',
            __DIR__ . '/../../Fixtures/sys_workspace.csv',
            __DIR__ . '/../../Fixtures/be_groups.csv',
        ],
    ];

    public function _initialize(): void
    {
        $typo3Version = GeneralUtility::makeInstance(Typo3Version::class);
        if ($typo3Version->getMajorVersion() === 13) {
            $this->localConfig['testExtensionsToLoad'] = [
                'typo3conf/ext/container',
                'typo3conf/ext/container_example',
            ];
        }
        parent::_initialize();
    }

    public function bootstrapTypo3Environment(SuiteEvent $suiteEvent): void
    {
        parent::bootstrapTypo3Environment($suiteEvent);
        $typo3Version = GeneralUtility::makeInstance(Typo3Version::class);
        if ($typo3Version->getMajorVersion() < 13) {
            return;
        }
        $testbase = new Testbase();
        $copyFiles = [
            // Create favicon.ico to suppress potential javascript errors in console
            // which are caused by calling a non html in the browser, e.g. seo sitemap xml
            'typo3/sysext/backend/Resources/Public/Icons/favicon.ico' => [
                'favicon.ico',
            ],
            // Provide some files into the test instance normally added by installer
            'typo3/sysext/install/Resources/Private/FolderStructureTemplateFiles/root-htaccess' => [
                '.htaccess',
            ],
            'typo3/sysext/install/Resources/Private/FolderStructureTemplateFiles/resources-root-htaccess' => [
                'fileadmin/.htaccess',
            ],
            'typo3/sysext/install/Resources/Private/FolderStructureTemplateFiles/fileadmin-temp-htaccess' => [
                'fileadmin/_temp_/.htaccess',
            ],
            'typo3/sysext/install/Resources/Private/FolderStructureTemplateFiles/fileadmin-temp-index.html' => [
                'fileadmin/_temp_/index.html',
            ],
            'typo3/sysext/install/Resources/Private/FolderStructureTemplateFiles/typo3temp-var-htaccess' => [
                'typo3temp/var/.htaccess',
            ],
        ];
        foreach ($copyFiles as $sourceFile => $targetFiles) {
            foreach ($targetFiles as $targetFile) {
                $testbase->createDirectory(dirname(ltrim($targetFile, '/')));
                copy(
                    from: ltrim($sourceFile, '/'),
                    to: ltrim($targetFile, '/'),
                );
            }
        }
    }
}
