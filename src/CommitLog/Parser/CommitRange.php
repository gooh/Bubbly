<?php namespace com\github\gooh\Bubbly\CommitLog\Parser;

use com\github\gooh\Range\NumericRange;

class CommitRange extends NumericRange
{
    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('HEAD~%d..HEAD~%d', $this->end, $this->start);
    }
}
