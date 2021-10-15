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

namespace WickedOne\PHPUnitPrinter;

use const PHP_SAPI;
use PHPUnit\Framework\TestFailure;
use PHPUnit\TextUI\DefaultResultPrinter;

/**
 * PhpStorm Printer.
 *
 * @author wicliff <wicliff.wolda@gmail.com>
 */
class PhpStormPrinter extends DefaultResultPrinter
{
    /**
     * @param \PHPUnit\Framework\TestFailure $defect
     * @param int                            $count
     */
    protected function printDefect(TestFailure $defect, int $count): void
    {
        parent::printDefect($defect, $count);

        if (PHP_SAPI === 'cli') {
            $this->printDefectFooter($defect);
        }
    }

    /**
     * @param \PHPUnit\Framework\TestFailure $defect
     */
    private function printDefectFooter(TestFailure $defect): void
    {
        $trace = explode(\PHP_EOL, trim((string) $defect->thrownException()));
        $offender = end($trace);

        // the things you do to please infection... ;-)
        if (false === \is_int(strpos($offender, ':'))) {
            return;
        }

        [$file, $line] = explode(':', $offender);

        if (isset($file, $line)) {
            $this->write(sprintf(
                "✏️  phpstorm://open?file=%s&line=%d\n",
                $file,
                $line
            ));
        }
    }
}
