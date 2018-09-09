<?php

namespace GoFinTech\Metrics;

class MetricsClient
{

    private $writer;

    /**
     * Constructs a new Metrics instance.
     * One instance per application is enough.
     */
    public function __construct()
    {
        $this->writer = new MetricsWriter();
    }

    /**
     * Forces cached data to be transferred.
     * Useful during graceful shutdown.
     */
    public function flush()
    {
        $this->writer->flush();
    }

    /**
     * Reports idle state.
     * Useful to report 'alive' state.
     */
    public function idlePing()
    {
        $this->writer->push(new MetricsEvent('IDLE'));
    }


    /**
     * Reports start of request.
     * Timestamps are captured in returned MetricsContext.
     */
    public function beginRequest(): MetricsContext
    {
        return new MetricsContext($this->writer);
    }
}
