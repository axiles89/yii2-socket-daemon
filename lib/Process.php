<?php
/**
 * Process.php
 *
 * @package axiles89\socket
 * @date: 15.12.2015 19:34
 * @author: Kyshnerev Dmitriy <dimkysh@mail.ru>
 */

namespace axiles89\socket;


/**
 * Class Process
 * @package axiles89\socket
 */
class Process
{
    const PID_FILE = 'sockethandler';
    const LOCK_FILE = 'sockethandler';

    /**
     * @var
     */
    protected $name;

    /**
     * @var null
     */
    private $lock = null;

    /**
     * Process constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPidFile()
    {
        return sys_get_temp_dir() . DIRECTORY_SEPARATOR . self::PID_FILE . '.' . $this->name . '.pid';
    }

    /**
     * @return string
     */
    public function getLockFile()
    {
        return sys_get_temp_dir() . DIRECTORY_SEPARATOR . self::LOCK_FILE . '.' . $this->name . '.lock';
    }

    /**
     * @param $pid
     */
    public function setPid($pid)
    {
        file_put_contents($this->getPidFile(), $pid);
    }

    /**
     * @return bool|resource
     */
    public function lock()
    {
        $fp = fopen($this->getLockFile(), "w+");

        if (flock($fp, LOCK_EX | LOCK_NB)) {
            return $this->lock = $fp;
        }

        return false;
    }

    /**
     *
     */
    public function stop() {
        if (file_exists($file = $this->getPidFile())) {
            $pid = (int)file_get_contents($this->getPidFile());
        }

        if (isset($pid) && $pid) {
            posix_kill($pid, SIGUSR1);
        }

        if (file_exists($file = $this->getPidFile()) && is_writable($file)) {
            unlink($file);
        }

        $this->release();
    }

    /**
     *
     */
    public function release()
    {
        if ($this->lock != null) {
            $fp = $this->lock;
        } else {
            $fp = fopen($this->getLockFile(), "w+");
        }

        if (is_resource($fp)) {
            flock($fp, LOCK_UN);
            fclose($fp);

            if (file_exists($file = $this->getLockFile()) && is_writable($file)) {
                unlink($file);
            }
        }

        $this->lock = null;
    }

    /**
     * @return bool
     */
    public function isRunning()
    {
        $fp = fopen($this->getLockFile(), "w+");

        if (!flock($fp, LOCK_SH | LOCK_NB)) {
            fclose($fp);
            return true;
        }

        fclose($fp);
        return false;
    }

}