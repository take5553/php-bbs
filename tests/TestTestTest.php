<?php

use PHPUnit\Framework\TestCase;

require('TestTest.php');

class TestTestTest extends TestCase
{
    public function testtestHoge()
    {
        $testtest = new TestTest;
        $this->assertEquals('hogehoge', $testtest->testHoge());
    }

    public function testtestAdd()
    {
        $testtest = new TestTest;
        $this->assertEquals(5, $testtest->testAdd(2, 3));
    }
}
