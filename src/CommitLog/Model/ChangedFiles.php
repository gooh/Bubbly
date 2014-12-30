<?php namespace com\github\gooh\Bubbly\CommitLog\Model;

class ChangedFiles implements \IteratorAggregate, \JsonSerializable
{
    /**
     * @var ChangedFile[]
     */
    private $changedFiles = [];

    /**
     * @var int
     */
    private $changes = 0;

    /**
     * @var int
     */
    private $testFileChanges = 0;

    /**
     * @var float
     */
    private $testCodeRatio = 0.0;

    /**
     * @return \ArrayIterator|ChangedFile[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->changedFiles);
    }

    /**
     * @param ChangedFile $changedFile
     */
    public function addChangedFile(ChangedFile $changedFile)
    {
        $this->changedFiles[] = $changedFile;
        $this->updateCommitMetrics($changedFile);
    }

    private function updateCommitMetrics(ChangedFile $changedFile)
    {
        $this->changes += $changedFile->getChanges();
        $this->testFileChanges += $changedFile->getTestFileChanges();

        if ($this->changes !== 0) {
            $this->testCodeRatio = round($this->testFileChanges / $this->changes, 2);
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
