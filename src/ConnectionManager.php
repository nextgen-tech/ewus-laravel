<?php
declare(strict_types=1);

namespace NGT\Laravel\Ewus;

use Illuminate\Support\Manager;
use NGT\Ewus\Connections\HttpConnection;
use NGT\Ewus\Connections\SoapConnection;
use NGT\Ewus\Contracts\Connection;

class ConnectionManager extends Manager
{
    /**
     * Create an instance of HTTP connection driver.
     *
     * @return  \NGT\Ewus\Connections\HttpConnection
     */
    public function createHttpDriver(): HttpConnection
    {
        return new HttpConnection($this->config->get('ewus.connections.http') ?? []);
    }

    /**
     * Create an instance of SOAP connection driver.
     *
     * @return  \NGT\Ewus\Connections\SoapConnection
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
