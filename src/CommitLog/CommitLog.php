<?php namespace com\github\gooh\Bubbly\CommitLog;

class CommitLog
{
    /**
     * @var Model\Commits
     */
    private $commits;

    /**
     * @var Parser\CommitLogParser
     */
    private $commitLogParser;

    /**
     * @var string
     */
    private $testFileRegexPattern;

    /**
     * @param Parser\CommitLogParser $commitLogParser
     * @param string $testFileRegexPattern
     */
    public function __construct(Parser\CommitLogParser $commitLogParser, $testFileRegexPattern)
    {
        $this->commitLogParser = $commitLogParser;
        $this->testFileRegexPattern = (string) $testFileRegexPattern;
    }

    /**
     * @param int $start
     * @param int $end
     * @return Model\Commits
     */
    public function findByStartAndEnd($start, $end)
    {
        $this->commits = new Model\Commits;

        foreach ($this->commitLogParser->parseFromTo($start, $end) as $event => $val) {
            if (method_exists($this, $event)) {
                call_user_func_array([$this, $event], $val);
            }
        }

        return $this->commits;
    }

    /**
     * @param string $commitHash
     */
    private function onCommit($commitHash)
    {
        $this->commits->addCommit(
            $this->createCommit($commitHash)
        );
    }

    /**
     * @param string $author
     */
    private function onAuthor($author)
    {
        $this->commits->getLastCommit()->setAuthor($author);
    }

    /**
     * @param string $date
     */
    private function onDate($date)
    {
        $this->commits->getLastCommit()->setDate(
            new \DateTimeImmutable($date)
        );
    }

    /**
     * @param int $additions
     * @param int $deletions
     * @param string $file
     */
    private function onFile($additions, $deletions, $file)
    {
        $this->commits->getLastCommit()->addFile(
            $this->createFile(
                $file,
                $additions,
                $deletions,
                preg_match($this->testFileRegexPattern, $file)
            )
        );
    }

    /**
     * @param string $message
     */
    private function onMessage($message)
    {
        $this->commits->getLastCommit()->appendMessage(trim($message, ' '));
    }

    /**
     * @param $commitHash
     * @return Model\Commit
     */
    private function createCommit($commitHash)
    {
        return new Model\Commit($commitHash);
    }

    /**
     * @param string $fileName
     * @param int $additions
     * @param int $deletions
     * @param bool $isTestFile
     * @return Model\ChangedFile
     */
    private function createFile($fileName, $additions, $deletions, $isTestFile)
    {
        return new Model\ChangedFile($fileName, $additions, $deletions, $isTestFile);
    }
}

