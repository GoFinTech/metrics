<?php

/*
 * This is a Kubernetes liveness probe based on metrics library usage
 */

function getFileTime(string $fileName)
{
    if (!file_exists($fileName))
        return null;
    $ts = filemtime($fileName);
    if ($ts === false)
        return null;
    return new DateTime("@$ts");
}

$now = new DateTime();

// If idle or reqstart doesn't happen in 1 minute, the batch is probably stuck

$minute = $now->modify('-1 minute');

$dt = getFileTime('/tmp/timestamp.idle');
if (!is_null($dt) && $dt > $minute) {
    echo "IDLE event is recent\n";
    exit(0);
}

$dt = getFileTime('/tmp/timestamp.reqstart');
if (!is_null($dt) && $dt > $minute) {
    echo "REQSTART event is recent\n";
    exit(0);
}

echo "NO recent IDLE or REQSTART\n";
exit(1);
