<?php
declare(strict_types=1);

namespace Etermed\Laravel\Ewus\Commands;

use Etermed\Ewus\Exceptions\ResponseException;
use Etermed\Ewus\Handler;
use Etermed\Ewus\Requests\ChangePasswordRequest;
use Etermed\Ewus\Requests\LoginRequest;
use Etermed\Ewus\Responses\LoginResponse;
use Etermed\Laravel\Ewus\Password;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Support\Str;
use Throwable;

class PasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ewus:password
                            {--R|random : Whether the password should be random generated}
                            {--init : Set current password (first run only)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set eWUŚ password.';

    /**
     * The config instance.
     *
     * @var  \Illuminate\Contracts\Config\Repository
     */
    private $config;

    /**
     * The password storage instance.
     *
     * @var  \Etermed\Laravel\Ewus\Password
     */
    private $password;

    /**
     * The handler instance.
     *
     * @var  \Etermed\Ewus\Handler
     */
    private $handler;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(Config $config, Password $password, Handler $handler): void
    {
        $this->config   = $config;
        $this->password = $password;
        $this->handler  = $handler;

        $newPassword = $this->newPassword();

        if ($this->option('init') === true) {
            $this->initPassword($newPassword);

            return;
        }

        $oldPassword = $this->password->get();

        try {
            $loginRequest = $this->loginRequest($oldPassword);
            /** @var \Etermed\Ewus\Responses\LoginResponse */
            $login = $this->handler->handle($loginRequest);

            $changePasswordRequest = $this->changePasswordRequest($login, $oldPassword, $newPassword);
            /** @var \Etermed\Ewus\Responses\ChangePasswordResponse */
            $changePassword = $this->handler->handle($changePasswordRequest);
        } catch (ResponseException $e) {
            $this->error("[{$e->getType()}] {$e->getMessage()}");

            return;
        } catch (Throwable $e) {
            $this->error("[Unknown] {$e->getMessage()}");

            return;
        }

        $this->password->set($newPassword);

        $this->info("[API] {$changePassword->getChangePasswordMessage()}" ?? '');
    }

    /**
     * Initialize password file.
     *
     * @param   string  $password
     * @return  void
     */
    private function initPassword(string $password): void
    {
        $this->password->set($password);

        $this->info('Password successfuly initialized.');
    }

    /**
     * Get new password.
     *
     * @return  string
     */
    private function newPassword(): string
    {
        if ($this->option('random') === true) {
            return $this->randomPassword();
        }

        return $this->ask('New password');
    }

    /**
     * Generate random password.
     *
     * @return  string
     */
    private function randomPassword(): string
    {
        return Str::random($this->config->get('ewus.password.length'));
    }

    /**
     * Get login request to eWUŚ.
     *
     * @param   string  $currentPassword
     * @return  \Etermed\Ewus\Requests\LoginRequest
     */
    private function loginRequest(string $currentPassword): LoginRequest
    {
        return new LoginRequest(
            $this->config->get('ewus.credentials.domain'),
            $this->config->get('ewus.credentials.login'),
            $currentPassword,
            $this->config->get('ewus.credentials.operator_id'),
            $this->config->get('ewus.credentials.operator_type'),
        );
    }

    /**
     * Get change password request.
     *
     * @param   \Etermed\Ewus\Responses\LoginResponse  $login
     * @param   string                                 $oldPassword
     * @param   string                                 $newPassword
     * @return  \Etermed\Ewus\Requests\ChangePasswordRequest
     */
    private function changePasswordRequest(
        LoginResponse $login,
        string $oldPassword,
        string $newPassword
    ): ChangePasswordRequest {
        return new ChangePasswordRequest(
            $login->getSessionId(),
            $login->getToken(),
            $this->config->get('ewus.credentials.domain'),
            $this->config->get('ewus.credentials.login'),
            $oldPassword,
            $newPassword,
            $this->config->get('ewus.credentials.operator_id'),
            $this->config->get('ewus.credentials.operator_type'),
        );
    }
}
