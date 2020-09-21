<?php
declare(strict_types=1);

namespace Etermed\Laravel\Ewus;

use Etermed\Ewus\Connections\HttpConnection;
use Etermed\Ewus\Connections\SoapConnection;
use Etermed\Ewus\Contracts\Connection;
use Illuminate\Support\Manager;

class ConnectionManager extends Manager
{
    /**
     * Create an instance of HTTP connection driver.
     *
     * @return  \Etermed\Ewus\Connections\HttpConnection
     */
    public function createHttpDriver(): HttpConnection
    {
        return new HttpConnection($this->config->get('ewus.connections.http') ?? []);
    }

    /**
     * Create an instance of SOAP connection driver.
     *
     * @return  \Etermed\Ewus\Connections\SoapConnection
     */
    public function createSoapDriver(): SoapConnection
    {
        return new SoapConnection($this->config->get('ewus.connections.soap') ?? []);
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('ewus.connection', 'http');
    }
}
