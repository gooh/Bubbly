<?php namespace com\github\gooh\ResourceHandle;

interface Writable
{
    /**
     * Writes given data to the resource up to the given length
     *
     * @throws ResourceException
     * @param string $data
     * @param int $length
     * @return int
     */
    public function write($data, $length = null);
}
