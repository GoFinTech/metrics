<?php

namespace GoFinTech\Metrics;

use \DateTime;

class MetricsWriter
{
    /*
     * Currently we do not do actual reporting but mainly do activity tracking.
     * For that we record seen events as timestamps of files:
     * - IDLE pings are reflected in a timestamp of /tmp/timestamp.idle
     * - REQSTART - /tmp/timestamp.reqstart
     * - REQEND - /tmp/timestamp.reqend
     *
     * In order to prevent unnecessary I/O we limit timestamp updates to every 5 seconds.
     */

    private $nextWrite;
    private $seen = [];

    public function flush()
    {
        // Apparently we do nothing here
    }

    public function push(MetricsEvent $event)
    {
        $seen[$event->code()] = true;
        if (isset($this->nextWrite) && $this->nextWrite > $event->timestamp())
            return;
        $this->nextWrite = (new DateTime())->modify('+5 seconds');
        if (isset($seen['IDLE']))
            touch('/tmp/timestamp.idle');
        if (isset($seen['REQSTART']))
            touch('/tmp/timestamp.reqstart');
        if (isset($seen['REQEND']))
            touch('/tmp/timestamp.reqend');
        $seen = [];
    }
}
