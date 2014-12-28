<?php namespace com\github\gooh\Bubbly;

use com\github\gooh\Bubbly\CommitLog\CommitLog;
use com\github\gooh\Bubbly\CommitLog\Parser\CommitLogParser;
use com\github\gooh\Bubbly\CommitLog\Parser\GitShowNumStat;
use com\github\gooh\Bubbly\Controller\HttpController;

include __DIR__ . '/../autoload.php';
include __DIR__ . '/../lib/autoload.php';

$config = include __DIR__ . '/../config.php';

$controller = new HttpController(
    new CommitLog(
        new CommitLogParser(
            new GitShowNumStat(
                $config['pathToGitRepository']
            )
        ),
        $config['testFileRegexPattern']
    )
);

header('Content-Type: application/json');
echo json_encode($controller->handleRequest($_GET));



