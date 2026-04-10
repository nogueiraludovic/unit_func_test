<?php

declare(strict_types=1);

namespace Vd\VdCore\Tests\Unit\Utility;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vd\VdCore\Utility\TcaUtility;

final class TcaUtilityTest extends TestCase
{
    #[Test]
    public function getFieldConfigurationCoversAllBranches(): void
    {
        $this->assertSame([], TcaUtility::getFieldConfiguration('unknown'));

        $description = TcaUtility::getFieldConfiguration('description');
        $this->assertSame('text', $description['config']['type']);
        $this->assertTrue($description['exclude']);

        $disable = TcaUtility::getFieldConfiguration('disable');
        $this->assertSame('check', $disable['config']['type']);

        $hidden = TcaUtility::getFieldConfiguration('hidden');
        $this->assertSame('check', $hidden['config']['type']);

        $editlock = TcaUtility::getFieldConfiguration('editlock');
        $this->assertSame('check', $editlock['config']['type']);
        $this->assertSame('HIDE_FOR_NON_ADMINS', $editlock['displayCond']);

        $endtime = TcaUtility::getFieldConfiguration('endtime');
        $this->assertSame('input', $endtime['config']['type']);
        $this->assertSame('datetime,int', $endtime['config']['eval']);

        $feGroup = TcaUtility::getFieldConfiguration('fe_group');
        $this->assertSame('select', $feGroup['config']['type']);
        $this->assertCount(3, $feGroup['config']['items']);

        $l10nDiff = TcaUtility::getFieldConfiguration('l10n_diffsource');
        $this->assertSame('passthrough', $l10nDiff['config']['type']);

        $l10nParent = TcaUtility::getFieldConfiguration('l10n_parent', [], 'my_table');
        $this->assertSame('my_table', $l10nParent['config']['foreign_table']);
        $this->assertStringContainsString('{#my_table}', $l10nParent['config']['foreign_table_where']);

        $l10nSource = TcaUtility::getFieldConfiguration('l10n_source');
        $this->assertSame('passthrough', $l10nSource['config']['type']);

        $starttime = TcaUtility::getFieldConfiguration('starttime');
        $this->assertSame('input', $starttime['config']['type']);

        $sysLanguage = TcaUtility::getFieldConfiguration('sys_language_uid');
        $this->assertSame('language', $sysLanguage['config']['type']);

        $custom['config']['default'] = 'x';
        $merged = TcaUtility::getFieldConfiguration('description', $custom);
        $this->assertSame('x', $merged['config']['default']);
    }
}
