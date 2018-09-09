<?php

namespace GoFinTech\Metrics;

use \DateTime;

class MetricsContext
{

    private $writer;
    private $startTime;
    private $reported;

    public function __construct(MetricsWriter $writer)
    {
        $this->writer = $writer;
        $this->startTime = new DateTime();
        $this->writer->push(new MetricsEvent('REQSTART', $this->startTime));
    }

    /**
     * Reports request finish with Success result.
     */
    public function endSuccess()
    {
        $this->endRequest('SUCCESS');
    }

    /**
     * Reports request finish with Failure result.
     */
    public function endFailure()
    {
        $this->endRequest('FAIL');
    }

    /**
     * Reports request finish with Unknown result unless
     * the result was previously reported with end* methods.
     */
    public function finish()
    {
        $this->endRequest('UNKNOWN');
    }

    private function endRequest(string $result)
    {
        if ($this->reported)
            return;
        $endTime = new DateTime();
        $event = new MetricsEvent('REQEND', $endTime);
        $event['result'] = $result;
        $event['length'] = $this->diffInSeconds($this->startTime, $endTime);
        $this->writer->push($event);
        $this->reported = true;
    }

    private function diffInSeconds(DateTime $start, DateTime $finish)
    {
        $diff = $start->diff($finish);
        $seconds = $diff->h * 3600 + $diff->i * 60 + $diff->s + $diff->f;
        return $seconds;
    }
}
