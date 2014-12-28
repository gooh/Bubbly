<?php namespace com\github\gooh\Bubbly\CommitLog\Model;

class Commit implements \JsonSerializable
{
    /**
     * @var string
     */
    private $commitHash;

    /**
     * @var string
     */
    private $author;

    /**
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * @var ChangedFiles
     */
    private $changedFiles;

    /**
     * @var string
     */
    private $message;

    /**
     * @param string $commitHash
     */
    public function __construct($commitHash)
    {
        $this->commitHash = (string) $commitHash;
        $this->changedFiles = new ChangedFiles;
        $this->message = '';
    }

    /**
     * @param $author
     */
    public function setAuthor($author)
    {
        $this->author = (string) $author;
    }

    /**
     * @param \DateTimeImmutable $date
     */
    public function setDate(\DateTimeImmutable $date)
    {
        $this->date = $date;
    }

    /**
     * @param ChangedFile $changedFile
     */
    public function addFile(ChangedFile $changedFile)
    {
        $this->changedFiles->addChangedFile($changedFile);
    }

    /**
     * @param $message
     */
    public function appendMessage($message)
    {
        $this->message .= (string) $message;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $properties = get_object_vars($this);
        $properties['date'] = $properties['date']->getTimestamp();

        return $properties;
    }
}
