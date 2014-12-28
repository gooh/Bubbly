<?php namespace com\github\gooh\Process\Unidirectional;

use com\github\gooh\ResourceHandle as rh;
use com\github\gooh\Process\ProcessException;

class WriteOnlyProcess extends UnidirectionalProcess implements rh\Writable
{
    /**
     * @return resource|false
     */
    protected function popen()
    {
        return popen($this->getCommand(), 'w');
    }

    /**
     * @throws ProcessException
     * @param string $data
     * @param int $length
     * @return int
     */
    public function write($data, $length = null)
    {
        $this->assertOpenProcHandle();

        $bytesWritten = $length !== null
            ? fwrite($this->getProcHandle(), $data, $length)
            : fwrite($this->getProcHandle(), $data);

        if (false !== $bytesWritten) {
            return $bytesWritten;
        }

        throw new ProcessException("Cannot write to Process '{$this->getCommand()}'");
    }
}
