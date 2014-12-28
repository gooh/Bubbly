<?php namespace com\github\gooh\Process;

class ProcessIterator implements \Iterator
{
    /**
     * @var Process
     */
    private $process;

    /**
     * @var string
     */
    private $currentBuffer;

    /**
     * @var int
     */
    private $currentOffset = 0;

    /**
     * @param Process $process
     */
    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return string
     */
    public function current()
    {
        return $this->currentBuffer;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->currentBuffer = $this->process->read();
        $this->currentOffset = $this->currentOffset += strlen($this->currentBuffer);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->currentOffset;
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
        return $this->process->hasData();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->process->close();
        $this->currentBuffer = '';
        $this->currentOffset = 0;
        $this->process->open();
    }
}
