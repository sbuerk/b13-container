<?php

declare(strict_types=1);

namespace B13\Container\Tests\Functional\Datahandler\Localization\ConnectedMode;

/*
 * This file is part of TYPO3 CMS-based extension "container" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use B13\Container\Tests\Functional\Datahandler\AbstractDatahandler;

class ContainerChildrenTest extends AbstractDatahandler
{
    /**
     * @test
     */
    public function cannotChangeLanguageOfTranslatedChild(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/ContainerChildren/translated_container_with_children.csv');
        $datamap = [
            'tt_content' => [
                22 => [
                    'sys_language_uid' => 0,
                ],
            ],
        ];
        $this->dataHandler->start($datamap, [], $this->backendUser);
        $this->dataHandler->process_datamap();
        self::assertCSVDataSet(__DIR__ . '/Fixtures/ContainerChildren/translated_container_with_children.csv');
        self::assertNotEmpty($this->dataHandler->errorLog, 'dataHander error log is empty');
    }

    /**
     * @test
     */
    public function cannotChangeL18nParentOfTranslatedChild(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/ContainerChildren/translated_container_with_children.csv');
        $datamap = [
            'tt_content' => [
                22 => [
                    'l18n_parent' => 1,
                ],
            ],
        ];
        $this->dataHandler->start($datamap, [], $this->backendUser);
        $this->dataHandler->process_datamap();
        self::assertCSVDataSet(__DIR__ . '/Fixtures/ContainerChildren/translated_container_with_children.csv');
        self::assertNotEmpty($this->dataHandler->errorLog, 'dataHander error log is empty');
    }
}
