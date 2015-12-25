<?php
/**
 * Application.php
 *
 * @package axiles89\socket
 * @date: 11.12.2015 14:18
 * @author: Kyshnerev Dmitriy <dimkysh@mail.ru>
 */

namespace axiles89\socket;


/**
 * Class Application
 * @package axiles89\socket
 */
class Application
{
    /**
     * @var array
     */
    protected $daemon = [];

    /**
     * @var
     */
    protected $user;

    /**
     * @var array
     */
    protected $children = [];

    /**
     * @var bool
     */
    protected $stop = false;

    /**
     * @param BaseDaemon $daemon
     * @return $this
     */
    public function addDaemon(BaseDaemon $daemon) {
        $this->daemon[] = $daemon;
        return $this;
    }

    /**
     * @param $name
     */
    public function deleteDaemon($name) {
        foreach ($this->daemon as $keyDaemon => $valueDaemon) {
            if ($valueDaemon->name == $name) {
                unset($this->daemon[$keyDaemon]);
            }
        }
    }

    /**
     *
     */
    private function signalHandlersChildren() {
        $root = $this;

        pcntl_signal(SIGUSR1, function() use ($root) {
            $root->stop = true;
        });

        pcntl_signal_dispatch();
    }

    /**
     *
     */
    private function signalHandlersParent() {
        $root = $this;

        pcntl_signal(SIGCHLD, function() use ($root) {
            while (($pid = pcntl_wait($status, WNOHANG)) > 0) {
                array_diff($root->children, [$pid]);
            }
        });

        pcntl_signal_dispatch();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function run() {

        if (count($this->daemon) == 0) {
            return false;
        }

        foreach ($this->daemon as $daemon) {

            $pidFile = $daemon->getProcess()->getPidFile();
            $lockFile = $daemon->getProcess()->getLockFile();

            if (is_file($pidFile) && is_writable($pidFile)) {
                unlink($pidFile);
            }

            if (is_file($lockFile) && is_writable($lockFile)) {
                unlink($lockFile);
            }

            $this->signalHandlersParent();

            declare(ticks=1);

            $pid = pcntl_fork();

            if ($pid > 0) {
                $this->children[] = $pid;
            }

            if ($pid == 0){
                posix_setsid();

                $this->changeUser($daemon);
                $this->signalHandlersChildren();
                $daemon->getProcess()->setPid(posix_getpid());
                $daemon->getProcess()->lock();

                $stop = false;

                $socket = SocketFactory::create($daemon->getType(), $daemon);

                while (!$this->stop) {
                    $socket->read();
                    pcntl_signal_dispatch();
                    sleep(3);
                }

                $socket->close();
                exit(1);
            }
        }
    }


    /**
     * @param $daemon
     * @throws \Exception
     */
    private function changeUser($daemon)
    {
        $user = $this->user;
        if ($user) {
            $user = posix_getpwnam($user);

            if (posix_geteuid() !== (int)$user['uid']) {
                posix_setgid($user['gid']);
                posix_setuid($user['uid']);
                if (posix_geteuid() !== (int)$user['uid']) {
                    $message = "Unable to change user to {$user['uid']}";
                    throw new \Exception($message);
                }
            }
        }
    }


    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


    /**
     * @return array
     */
    public function getDaemon()
    {
        return $this->daemon;
    }


}