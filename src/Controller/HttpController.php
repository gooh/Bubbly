<?php namespace com\github\gooh\Bubbly\Controller;

use com\github\gooh\Bubbly\CommitLog\CommitLog;

class HttpController
{
    /**
     * @var CommitLog
     */
    private $commitLog;

    /**
     * @param CommitLog $commitLog
     */
    public function __construct(CommitLog $commitLog)
    {
        $this->commitLog = $commitLog;
    }

    /**
     * @param $request
     * @return string
     */
    public function handleRequest(array $request)
    {
        try {
            header('Status: 200 OK', true);
            return $this->commitLog->findByStartAndEnd(
                isset($request['start']) ? $request['start'] : 0,
                isset($request['end']) ? $request['end'] : 500
            );
        } catch (\Exception $e) {
            header('Status: 500 Internal Server Error', true);
            return ['error' => $e->getMessage()];
        }
    }
}
