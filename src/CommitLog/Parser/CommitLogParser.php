<?php namespace com\github\gooh\Bubbly\CommitLog\Parser;

class CommitLogParser
{
    /**
     * @var array
     */
    private $patterns = [
        'onCommit' => '(^commit\s+(?<commit>[a-z0-9]+)\n$)',
        'onAuthor' => '(^Author:\s+(?<author>.+)\n$)',
        'onDate' => '(^Date:\s+(?<date>.+)\n$)',
        'onFile' => '(^(?<additions>\d+)\t(?<deletions>\d+)\t(?<file>.+)\n$)',
        'onMessage' => '(^(?<message>.+\n)$)'
    ];

    /**
     * @var GitShowNumStat
     */
    private $gitShowNumStat;

    /**
     * @param GitShowNumStat $gitShowNumStat
     */
    public function __construct(GitShowNumStat $gitShowNumStat)
    {
        $this->gitShowNumStat = $gitShowNumStat;
    }

    /**
     * Parses GitShowNumStat in the given range and returns the parsed data
     *
     * @param int $start
     * @param int $end
     * @return array [string eventName => array matches]
     */
    public function parseFromTo($start, $end)
    {
        $this->gitShowNumStat->setStartAndEnd($start, $end);
        foreach ($this->gitShowNumStat as $commitLogLine) {
            foreach ($this->patterns as $event => $pattern) {
                if (preg_match($pattern, $commitLogLine, $matches)) {
                    yield $event => $this->getAssoc($matches);
                    continue 2;
                }
            }
        }
    }

    /**
     * Returns all array values with non-numeric keys
     *
     * @param array $matches
     * @return array
     */
    private function getAssoc(array $matches)
    {
        $data = [];
        foreach ($matches as $key => $val) {
            if (is_numeric($key)) {
                continue;
            }
            $data[$key] = $val;
        }

        return $data;
    }
}
