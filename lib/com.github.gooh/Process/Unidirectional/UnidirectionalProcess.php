<?php namespace com\github\gooh\Process\Unidirectional;

use com\github\gooh\ResourceHandle as rh;
use com\github\gooh\Process\ProcessException;

abstract class UnidirectionalProcess implements rh\ResourceHandle
{
    /**
     * @var string
     */
    private $command;

    /**
     * @var resource
     */
    private $procHandle;

    /**
     * @param string $command
     */
    public function __construct($command)
    {
        $this->command = (string) escapeshellcmd($command);
    }

    /**
     * Opens the Process
     *
     * @throws ProcessException
     * @return void
     */
    public function open()
    {
        if (is_resource($this->procHandle)) {
            return;
        }

        $procHandle = $this->popen();

        if (false !== $procHandle) {
            $this->setProcHandle($procHandle);
            return;
        }

        throw new ProcessException("Cannot open Process '{$this->getCommand()}'");
    }

    /**
     * @return resource|false
     */
    abstract protected function popen();

    /**
     * Closes the Process
     *
     * @throws ProcessException
     * @return void
     */
    public function close()
    {
        if (false === $this->hasOpenProcHandle()) {
            return;
        }

        $exitCode = pclose($this->getProcHandle());

        if (-1 !== $exitCode) {
            $this->setProcHandle(null);
            return;
        }

        throw new ProcessException("Cannot close Process '{$this->getCommand()}'");
    }

    /**
     * @return string
     */
    protected function getCommand()
    {
        return $this->command;
    }

    /**
     * Returns the procHandle
     *
     * @return resource|null
     */
    protected function getProcHandle()
    {
        return $this->procHandle;
    }

    /**
     * @param resource $procHandle
     */
    protected function setProcHandle($procHandle)
    {
        $this->procHandle = $procHandle;
    }

    /**
     * @return bool
     */
    protected function hasOpenProcHandle()
    {
        return is_resource($this->procHandle);
    }

    /**
     * @throws ProcessException
     */
    protected function assertOpenProcHandle()
    {
        if (false === $this->hasOpenProcHandle()) {
            throw new ProcessException("Process '{$this->getCommand()}' is not open");
        }
    }

    public function __destruct()
    {
        if ($this->hasOpenProcHandle()) {
            $this->close();
        }
    }
}
