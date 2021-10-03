<?php

declare(strict_types=1);

namespace WickedOne\PHPUnitPrinter\Tests;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ErrorTestCase;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestResult;
use WickedOne\PHPUnitPrinter\PhpStormPrinter;

/**
 * PhpStorm Printer Test.
 *
 * @author wicliff <wicliff.wolda@gmail.com>
 */
class PhpStormPrinterTest extends TestCase
{

    /**
     * check for presence of editor url in output file
     * and whether original messages are still printed
     */
    public function testPrintDefectFooter(): void
    {
        $result = new TestResult();
        $test = new ErrorTestCase('lorem ipsum');
        $throwable = new AssertionFailedError('wickedone/phpunit-printer error message');

        $result->addFailure($test, $throwable, time());

        $filename = sys_get_temp_dir() . '/phpunit-printer.txt';
        touch($filename);

        $printer = new PhpStormPrinter(fopen($filename, 'rb+'));
        $printer->printResult($result);

        $result = file_get_contents($filename);

        self::assertStringContainsString('wickedone/phpunit-printer error message', $result);
        self::assertStringContainsString('phpstorm://open?file=', $result);
        self::assertStringContainsString('PhpStormPrinterTest', $result);
        self::assertStringContainsString('&line=', $result);

        @unlink($filename);
    }
}
