<?php namespace com\github\gooh\Range;

abstract class Range
{
    /**
     * @var mixed
     */
    protected $start;

    /**
     * @var mixed
     */
    protected $end;

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $start
     * @return static
     */
    public function setStart($start)
    {
        return new static($start, $this->end);
    }

    /**
     * @param mixed $end
     * @return static
     */
    public function setEnd($end)
    {
        return new static($this->start, $end);
    }
}
