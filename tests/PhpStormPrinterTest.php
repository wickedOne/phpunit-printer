<?php

declare(strict_types=1);

/*
 * This file is part of WickedOne\PHPUnitPrinter.
 *
 * (c) wicliff <wicliff.wolda@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WickedOne\PHPUnitPrinter\Tests;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ErrorTestCase;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestResult;
use PHPUnit\Framework\TestSuite;
use WickedOne\PHPUnitPrinter\PhpStormPrinter;
use WickedOne\PHPUnitPrinter\Tests\Stub\EmptyTestClass;

/**
 * PhpStorm Printer Test.
 *
 * @author wicliff <wicliff.wolda@gmail.com>
 */
class PhpStormPrinterTest extends TestCase
{
    /**
     * check for presence of editor url in output file
     * and whether original messages are still printed.
     */
    public function testPrintDefectFooter(): void
    {
        $result = new TestResult();
        $test = new ErrorTestCase('lorem ipsum');
        $throwable = new AssertionFailedError('wickedone/phpunit-printer error message');

        $result->addFailure($test, $throwable, time());

        $filename = sys_get_temp_dir().'/phpunit-printer.txt';

        $printer = new PhpStormPrinter(fopen($filename, 'wb+'));
        $printer->printResult($result);

        $result = file_get_contents($filename);

        self::assertStringContainsString('wickedone/phpunit-printer error message', $result);
        self::assertStringContainsString('phpstorm://open?file=', $result);
        self::assertStringContainsString('PhpStormPrinterTest', $result);
        self::assertStringContainsString('&line=', $result);

        @unlink($filename);
    }

    /**
     * in some occasions no trace is provided.
     * make sure printing editor url isn't printed in those occasions.
     */
    public function testSkipDefectFooterOnWarning(): void
    {
        $result = (new TestSuite(EmptyTestClass::class))->run();

        $filename = sys_get_temp_dir().'/phpunit-printer.txt';

        $list = $GLOBALS['__PHPUNIT_ISOLATION_EXCLUDE_LIST'] ?? null;
        $GLOBALS['__PHPUNIT_ISOLATION_EXCLUDE_LIST'] = [__FILE__];

        $printer = new PhpStormPrinter(fopen($filename, 'wb+'));
        $printer->printResult($result);

        $GLOBALS['__PHPUNIT_ISOLATION_EXCLUDE_LIST'] = $list;

        $result = file_get_contents($filename);

        self::assertStringNotContainsString('phpstorm://open?file=', $result);

        @unlink($filename);
    }
}
