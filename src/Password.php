<?php
declare(strict_types=1);

namespace NGT\Laravel\Ewus;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Filesystem\Filesystem as Storage;

class Password
{
    /**
     * The config instance.
     *
     * @var  \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * The storage instance.
     *
     * @var  \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $storage;

    /**
     * The password manager constructor.
     *
     * @param  \Illuminate\Contracts\Config\Repository      $config
     * @param  \Illuminate\Contracts\Filesystem\Filesystem  $storage
     */
    public function __construct(Config $config, Storage $storage)
    {
        $this->config  = $config;
        $this->storage = $storage;
    }

    /**
     * Get filename of password file.
     *
     * @return  string
     */
    public function filename(): string
    {
        return $this->config->get('ewus.password.filename');
    }

    /**
     * Get stored password.
     *
     * @return  string
     */
    public function get(): string
    {
        return $this->storage->get($this->filename());
    }

    /**
     * Store password.
     *
     * @param  string  $password
     * @return bool
     */
    public function set(string $password): bool
    {
        return $this->storage->put($this->filename(), $password);
    }
}
