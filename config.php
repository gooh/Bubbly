<?php
return [
    /*
     * The path to the Git repository you want to visualize commits from.
     *
     * Defaults to Bubbly's application root.
     */
    'pathToGitRepository' => __DIR__ . '/../',

    /*
     * The pattern used to determine whether a file is a test file
     *
     * This pattern will be matched against the file names given in a commit.
     * The file names are usually given with a path relative to their repository
     * root.
     *
     * Defaults to all file paths containing the strings "fixtures" or "Tests"
     */
    'testFileRegexPattern' => '(.+fixtures.+|.+Tests.+)i'
];
