<?php namespace com\github\gooh\Range;

class NumericRange extends Range
{
    /**
     * @param mixed $start
     * @param mixed $end
     * @throws \InvalidArgumentException
     * @throws \DomainException
     */
    public function __construct($start, $end)
    {
        $this->assertStartAndEndAreNumeric($start, $end);
        $this->assertStartBeforeEnd($start, $end);
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @param mixed $start
     * @param mixed $end
     * @throws \InvalidArgumentException When $start or $end is not numeric
     */
    private function assertStartAndEndAreNumeric($start, $end)
    {
        if (false === is_numeric($start) || false === is_numeric($end)) {
            throw new \InvalidArgumentException("Start $start and End $end must be numeric");
        }
    }

    /**
     * @param mixed $start
     * @param mixed $end
     * @throws \DomainException When $start is not less than $end
     */
    private function assertStartBeforeEnd($start, $end)
    {
        if (false === $start < $end) {
            throw new \DomainException("Start $start must be less than End $end");
        }
    }
}
