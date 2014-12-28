<?php namespace com\github\gooh\Process;

use com\github\gooh\ResourceHandle as rh;

class Process implements rh\ResourceHandle, rh\Readable, rh\Writable, \IteratorAggregate
{
    /**
     * @var rh\ResourceHandle, rh\Readable, rh\Writable
     */
    private $process;

    /**
     * @param string $command
     * @return Process
     */
    public static function createReadOnly($command)
    {
        return new self(new Unidirectional\ReadOnlyProcess($command));
    }

    /**
     * @param string $command
     * @return Process
     */
    public static function createWriteOnly($command)
    {
        return new self(new Unidirectional\WriteOnlyProcess($command));
    }

    /**
     * @param rh\ResourceHandle $process
     */
    private function __construct(rh\ResourceHandle $process)
    {
        $this->process = $process;
    }

    /**
     * Opens the Process
     *
     * @throws ProcessException
     * @return void
     */
    public function open()
    {
        $this->process->open();
    }

    /**
     * Reads from the Process up the given length
     *
     * @throws ProcessException
     * @param int $length
     * @return string
     */
    public function read($length = null)
    {
        if ($this->process instanceof rh\Readable) {
            return $this->process->read($length);
        }

        throw new ProcessException('Process is not readable');
    }

    /**
     * Returns whether there is still data left to read
     *
     * @return bool
     * @throws ProcessException
     */
    public function hasData()
    {
        if ($this->process instanceof rh\Readable) {
            return $this->process->hasData();
        }

        throw new ProcessException('Process is not readable');
    }

    /**
     * Writes given data to the process up to the given length
     *
     * @throws ProcessException
     * @param string $data
     * @param int $length
     * @return int
     */
    public function write($data, $length = null)
    {
        if ($this->process instanceof rh\Writable) {
            return $this->process->write($data, $length);
        }

        throw new ProcessException('Process is not writable');
    }

    /**
     * Closes the Process
     *
     * @throws ProcessException
     * @return void
     */
    public function close()
    {
        $this->process->close();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return ProcessIterator
     */
    public function getIterator()
    {
        return new ProcessIterator($this);
    }
}
