<?php

use F1\Base\RecursiveSortedIterator;

class RecursiveSortedIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function test_constructor_should_accept_iterator_and_callback()
    {
        $i = new RecursiveArrayIterator([1, 2, 3]);
        $rsi = new RecursiveSortedIterator($i, function () {
            return 0;
        });
        $this->assertInstanceOf('F1\Base\RecursiveSortedIterator', $rsi);
    }

    public function test_should_enumerate_all_items()
    {
        $a = [1, 2, 3, [1, 2, 3]];
        $ai = new RecursiveArrayIterator($a);
        $rsi = new RecursiveSortedIterator($ai, function () {
            return 0;
        });
        $rii = new RecursiveIteratorIterator($rsi);
        $cnt = 0;
        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach ($rii as $item) {
            $cnt++;
        }
        $this->assertEquals(6, $cnt);
    }

    public function test_should_enumerate_items_sorted()
    {
        $a = [1, 3, 2, [5, 4, 6], 0];
        $ai = new RecursiveArrayIterator($a);
        $rsi = new RecursiveSortedIterator($ai, function ($a, $b) {
            if (is_array($a))
                return -1;
            if (is_array($b))
                return 1;
            return $a - $b;
        });
        $rii = new RecursiveIteratorIterator($rsi);
        $items = [];
        foreach ($rii as $item) {
            array_push($items, $item);
        }
        // the 4,5,6 must be at the beginning since we are putting arrays first
        $this->assertEquals([4, 5, 6, 0, 1, 2, 3], $items);
    }
}