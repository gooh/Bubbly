<?php namespace com\github\gooh\Bubbly\CommitLog\Model;

class ChangedFile implements \JsonSerializable
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * @var int
     */
    private $additions;

    /**
     * @var int
     */
    private $deletions;

    /**
     * @var boolean
     */
    private $isTestFile;

    /**
     * @param string $fileName
     * @param int $additions
     * @param int $deletions
     * @param bool $isTestFile
     */
    public function __construct($fileName, $additions, $deletions, $isTestFile)
    {
        $this->fileName = (string) $fileName;
        $this->additions = (int) $additions;
        $this->deletions = (int) $deletions;
        $this->isTestFile = (bool) $isTestFile;
    }

    /**
     * @return int
     */
    public function getChanges()
    {
        return $this->additions + $this->deletions;
    }

    /**
     * @return int
     */
    public function getTestFileChanges()
    {
        return $this->isTestFile
            ? $this->getChanges()
            : 0;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
