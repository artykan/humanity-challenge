<?php

namespace HumanityChallenge\Helpers;

use Helpers\DateHelper;
use PHPUnit\Framework\TestCase;

class DateHelperTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGetWorkingDaysCount()
    {

        $this->assertEquals(
            9,
            DateHelper::getWorkingDaysCount(
                '2018-12-24',
                '2019-01-04',
                ['2019-01-01']
            )
        );
    }
}