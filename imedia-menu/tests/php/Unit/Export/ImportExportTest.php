<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Export;

use IMedia\Menu\Export\Exporter;
use IMedia\Menu\Export\Importer;
use PHPUnit\Framework\TestCase;

final class ImportExportTest extends TestCase
{
    public function testExportGeneratesStructure(): void
    {
        $exporter = new Exporter();
        $result = $exporter->export();

        $this->assertArrayHasKey('version', $result);
        $this->assertArrayHasKey('exported', $result);
        $this->assertArrayHasKey('settings', $result);
        $this->assertArrayHasKey('panels', $result);
        $this->assertArrayHasKey('templates', $result);
        $this->assertArrayHasKey('menus', $result);
    }

    public function testExportIncludesPluginVersion(): void
    {
        $exporter = new Exporter();
        $result = $exporter->export();

        $this->assertSame('1.0.0', $result['version']);
    }

    public function testExportHasTimestamp(): void
    {
        $exporter = new Exporter();
        $result = $exporter->export();

        $this->assertNotEmpty($result['exported']);
    }

    public function testImportMissingVersionReturnsErrors(): void
    {
        $importer = new Importer();
        $result = $importer->import([]);

        $this->assertNotEmpty($result['errors']);
    }

    public function testImportWithVersion(): void
    {
        $importer = new Importer();
        $result = $importer->import(['version' => '1.0.0']);

        $this->assertEmpty($result['errors']);
    }

    public function testImportWithSettings(): void
    {
        $importer = new Importer();
        $result = $importer->import([
            'version'  => '1.0.0',
            'settings' => ['breakpoint' => '900px'],
        ]);

        $this->assertTrue($result['settings']);
    }

    public function testExportJson(): void
    {
        $exporter = new Exporter();
        $json = $exporter->exportJson();

        $this->assertJson($json);
        $this->assertStringContainsString('1.0.0', $json);
    }
}
