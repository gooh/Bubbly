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
            header('HTTP/1.0 200 OK', true, 200);
            return $this->commitLog->findByStartAndEnd(
                isset($request['start']) ? $request['start'] : 0,
                isset($request['end']) ? $request['end'] : 500
            );
        } catch (\Exception $e) {
            header('HTTP/1.0 500 Internal Server Error', true, 500);
            return ['error' => $e->getMessage()];
        }
    }
}
