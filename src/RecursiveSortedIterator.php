<?php

namespace F1\Base;

use RecursiveIterator;

class RecursiveSortedIterator implements RecursiveIterator
{
    private $iterator;
    private $callback;
    private $entries;
    private $current_index;

    /**
     * @param \RecursiveIterator $source
     * @param callable $comparison_callback
     */
    public function __construct($source, $comparison_callback)
    {
        $this->iterator = $source;
        $this->callback = $comparison_callback;
        $this->entries = null;
        $this->current_index = 0;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        if ($this->valid()) {
            return $this->entries[$this->current_index]['entry'];
        }
        return null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        if ($this->valid()) {
            $this->current_index++;
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        if ($this->valid()) {
            return $this->entries[$this->current_index]['key'];
        }
        return null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        if ($this->entries == null) {
            // on first access we build the entries array
            $this->buildEntries();
        }
        return $this->entries && $this->current_index < count($this->entries);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->current_index = 0;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Returns if an iterator can be created for the current entry.
     * @link http://php.net/manual/en/recursiveiterator.haschildren.php
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     */
    public function hasChildren()
    {
        if ($this->valid()) {
            return $this->entries[$this->current_index]['children'] != null;
        }
        return false;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Returns an iterator for the current entry.
     * @link http://php.net/manual/en/recursiveiterator.getchildren.php
     * @return RecursiveIterator An iterator for the current entry.
     */
    public function getChildren()
    {
        if ($this->valid()) {
            return $this->entries[$this->current_index]['children'];
        }
        return null;
    }

    /**
     * Populate the entries variable based on the current iterator.
     * Each entry is an array with the keys: children, key, and entry.
     */
    private function buildEntries()
    {
        $entries = array();
        $this->iterator->rewind();
        while ($this->iterator->valid()) {
            $e = array(
                'entry' => $this->iterator->current(),
                'key' => $this->iterator->key(),
                'children' => $this->iterator->hasChildren() ?
                    new RecursiveSortedIterator($this->iterator->getChildren(), $this->callback)
                    : null
            );
            $this->iterator->next();
            array_push($entries, $e);
        }
        usort($entries, function ($a, $b) {
            return call_user_func($this->callback, $a['entry'], $b['entry']);
        });
        $this->entries = $entries;
    }
}