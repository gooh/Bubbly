<?php namespace com\github\gooh\Bubbly\CommitLog\Model;

class Commits implements \IteratorAggregate, \JsonSerializable
{
    /**
     * @var Commit[]
     */
    private $commits = [];

    /**
     * @param Commit $commit
     */
    public function addCommit(Commit $commit)
    {
        $this->commits[] = $commit;
    }

    /**
     * @return Commit|null
     */
    public function getLastCommit()
    {
        return end($this->commits);
    }

    /**
     * @return Commit[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->commits);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
