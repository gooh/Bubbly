<?php
namespace com\github\gooh\Range;

class DateRange extends Range
{
    /**
     * @param string $format
     * @param mixed $start
     * @param mixed $end
     * @throws \DomainException
     * @return static
     */
    public static function createFromFormat($format, $start, $end)
    {
        return new static(
            \DateTimeImmutable::createFromFormat($format, $start),
            \DateTimeImmutable::createFromFormat($format, $end)
        );
    }

    /**
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @throws \DomainException
     */
    public function __construct(\DateTimeInterface $start, \DateTimeInterface $end)
    {
        $this->assertStartDateBeforeEndDate($start, $end);
        $this->start = clone $start;
        $this->end = clone $end;
    }

    /**
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @throws \DomainException when Start date is not before End date
     */
    private function assertStartDateBeforeEndDate(\DateTimeInterface $start, \DateTimeInterface $end)
    {
        if ($start >= $end) {
            throw new \DomainException(
                sprintf(
                    'Start Date %s must be before End Date %s',
                    $start->format(\DateTime::ISO8601),
                    $end->format(\DateTime::ISO8601)
                )
            );
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s - %s',
            $this->start->format(\DateTime::ISO8601),
            $this->end->format(\DateTime::ISO8601)
        );
    }
}
