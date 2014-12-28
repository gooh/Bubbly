<?php namespace com\github\gooh\ResourceHandle;

interface Readable
{
    /**
     * Reads from the resource up the given length
     *
     * @throws ResourceException
     * @param int $length
     * @return string
     */
    public function read($length = null);

    /**
     * Returns whether there is still data left to read
     *
     * @return bool
     */
    public function hasData();
}
