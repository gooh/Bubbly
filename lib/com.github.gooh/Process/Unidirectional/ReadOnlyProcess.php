<?php namespace com\github\gooh\Process\Unidirectional;

use com\github\gooh\ResourceHandle as rh;
use com\github\gooh\Process\ProcessException;

class ReadOnlyProcess extends UnidirectionalProcess implements rh\Readable
{
    /**
     * @return resource|false
     */
    protected function popen()
    {
        return popen($this->getCommand(), 'r');
    }

    /**
     * @throws ProcessException
     * @param int $length
     * @return string
     */
    public function read($length = null)
    {
        $this->assertOpenProcHandle();

        $buffer = $length !== null
            ? fgets($this->getProcHandle(), $length)
            : fgets($this->getProcHandle());

        if (false !== $buffer) {
            return $buffer;
        }

        if (false === $this->hasData()) {
            return '';
        }

        throw new ProcessException("Cannot read from Process '{$this->getCommand()}'");
    }

    /**
     * @return bool
     * @throws ProcessException
     */
    public function hasData()
    {
        $this->assertOpenProcHandle();

        return false === feof($this->getProcHandle());
    }
}
