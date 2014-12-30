<?php namespace com\github\gooh\Bubbly\CommitLog\Parser;

use com\github\gooh\Process\Process;

class GitShowNumStat implements \IteratorAggregate
{
    /**
     * @var string
     */
    private $pathToRepo;

    /**
     * @var string
     */
    private $prettyPrint = 'format:Commit:%H%nAuthor:%an%nDate:%ai%nSubject:%s';

    /**
     * @var CommitRange
     */
    private $commitRange;

    /**
     * @param string $pathToRepo
     */
    public function __construct($pathToRepo)
    {
        $this->pathToRepo = (string) $pathToRepo;
        $this->setStartAndEnd(0, 1);
    }

    /**
     * @param int $start
     * @param int $end
     */
    public function setStartAndEnd($start, $end)
    {
        $this->commitRange = new CommitRange($start, $end);
    }

    /**
     * @return Process
     */
    public function getIterator()
    {
        return Process::createReadOnly($this);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            'git -C %s show --pretty="%s" --numstat %s',
            escapeshellarg($this->pathToRepo),
            $this->prettyPrint,
            $this->commitRange
        );
    }
}
