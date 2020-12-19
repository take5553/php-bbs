<?php

class TestTest
{
    public function testHoge()
    {
        return 'hogehoge';
    }

    public function testAdd(int $a, int $b)
    {
        $number = $this->makeNumberArray($a);

        for ($i = 1; $i <= $b; $i++) {
            $number[] = 1;
        }

        return count($number);
    }

    private function makeNumberArray(int $number)
    {
        $numberArray = array();

        for ($i = 1; $i <= $number; $i++) {
            $numberArray[] = 1;
        }

        return $numberArray;
    }
}
