<?php
namespace Kapusta\tests;
use PHPUnit\Framework\TestCase as TestCaseOrigin;

class TestCase extends TestCaseOrigin
{
    protected function assertEqualsStrings($sExpected, $sActual)
    {
        $sExpected = preg_replace("/\s+/", "", $sExpected);
        $sActual = preg_replace("/\s+/", "", $sActual);
        $this->assertEquals($sExpected, $sActual);
    }
}