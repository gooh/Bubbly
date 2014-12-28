<?php namespace com\github\gooh\ResourceHandle;

interface ResourceHandle
{
    /**
     * Opens the ResourceHandle
     *
     * @throws ResourceException
     * @return void
     */
    public function open();

    /**
     * Closes the ResourceHandle
     *
     * @throws ResourceException
     * @return void
     */
    public function close();
}
