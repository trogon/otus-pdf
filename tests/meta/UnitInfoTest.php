<?php

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\meta\UnitInfo;
use trogon\otuspdf\base\InvalidCallException;

final class UnitInfoTest extends TestCase
{
    private $unitInfoClass = 'trogon\otuspdf\meta\UnitInfo';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedForMm()
    {
        $this->assertInstanceOf(
            $this->unitInfoClass,
            UnitInfo::mm()
        );
    }

    public function testCanBeCreatedForCm()
    {
        $this->assertInstanceOf(
            $this->unitInfoClass,
            UnitInfo::cm()
        );
    }

    public function testCanBeCreatedForInch()
    {
        $this->assertInstanceOf(
            $this->unitInfoClass,
            UnitInfo::inch()
        );
    }

    public function testConvertMmToMm()
    {
        $unitInfo = UnitInfo::mm();
        $this->assertEquals(
            64.516,
            $unitInfo->toMm(64.516)
        );
    }

    public function testConvertMmToCm()
    {
        $unitInfo = UnitInfo::mm();
        $this->assertEquals(
            6.4516,
            $unitInfo->toCm(64.516)
        );
    }

    public function testConvertMmToInch()
    {
        $unitInfo = UnitInfo::mm();
        $this->assertEquals(
            2.540,
            $unitInfo->toInch(64.516)
        );
    }

    public function testConvertCmToMm()
    {
        $unitInfo = UnitInfo::cm();
        $this->assertEquals(
            645.160,
            $unitInfo->toMm(64.516)
        );
    }

    public function testConvertCmToCm()
    {
        $unitInfo = UnitInfo::cm();
        $this->assertEquals(
            64.516,
            $unitInfo->toCm(64.516)
        );
    }

    public function testConvertCmToInch()
    {
        $unitInfo = UnitInfo::cm();
        $this->assertEquals(
            25.400,
            $unitInfo->toInch(64.516)
        );
    }

    public function testConvertInchToMm()
    {
        $unitInfo = UnitInfo::inch();
        $this->assertEquals(
            1638.7064,
            $unitInfo->toMm(64.516)
        );
    }

    public function testConvertInchToCm()
    {
        $unitInfo = UnitInfo::inch();
        $this->assertEquals(
            163.87064,
            $unitInfo->toCm(64.516)
        );
    }

    public function testConvertInchToInch()
    {
        $unitInfo = UnitInfo::inch();
        $this->assertEquals(
            64.516,
            $unitInfo->toInch(64.516)
        );
    }

    /**
     * @expectedException trogon\otuspdf\base\InvalidCallException
     */
    public function testConvertCustomToMm()
    {
        $unitInfo = new UnitInfo();
        $unitInfo->toMm(64.516);
    }

    /**
     * @expectedException trogon\otuspdf\base\InvalidCallException
     */
    public function testConvertCustomToCm()
    {
        $unitInfo = new UnitInfo();
        $unitInfo->toCm(64.516);
    }

    /**
     * @expectedException trogon\otuspdf\base\InvalidCallException
     */
    public function testConvertCustomToInch()
    {
        $unitInfo = new UnitInfo();
        $unitInfo->toInch(64.516);
    }
}
