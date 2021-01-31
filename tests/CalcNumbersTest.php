<?php

use App\Services\CalcNumbers;
use Tests\TestCase;

class CalcNumbersTest extends TestCase
{
    protected $obj;

    protected function setUp(): void
    {
        parent::setUp();

        $this->obj = new CalcNumbers;
    }

    public function testCalcFactor()
    {
        $ans = $this->obj->calc(16);

        $this->assertIsArray($ans['a']);
        $this->assertEquals([1, 2, 4, 8, 16], $ans['a']);
    }

    public function testCalcIncludePrimeNumbers()
    {
        $ans = $this->obj->calc(16);

        $this->assertIsArray($ans['b']);
        $this->assertEquals([3, 5, 7, 11, 13], $ans['b']);
    }

    public function testCalcPrimeFactors()
    {
        $ans = $this->obj->calc(16);

        $this->assertIsArray($ans['c']);
        $this->assertEquals([], $ans['c']);
    }
}
