<?php
/**
 * BaseDaemon.php
 *
 * @package axiles89\socket
 * @date: 11.12.2015 14:09
 * @author: Kyshnerev Dmitriy <dimkysh@mail.ru>
 */

namespace axiles89\socket;


use yii\base\Component;

/**
 * Class BaseDaemon
 * @package axiles89\socket
 */
abstract class BaseDaemon extends Component implements IDaemon
{
    /**
     * @var array
     */
    private $typeList = ['udp', 'tcp'];

    /**
     * @var int
     */
    protected $maxClient = 10;

    /**
     * @var
     */
    protected $name;

    /**
     * @var
     */
    protected $port;

    /**
     * @var
     */
    protected $ip;

    /**
     * @var
     */
    protected $type;

    /**
     * @var Process
     */
    protected $process;

    /**
     * BaseDaemon constructor.
     * @param int $port
     */
    public function __construct(Process $process, $config = [])
    {
        $this->process = $process;
        parent::__construct($config);
    }

    /**
     * Initializes the object.
     */
    public function init()
    {
        parent::init();

        if (!$this->port or !is_int($this->port)) {
            throw new \InvalidArgumentException("Please set port as type integer");
        }

        if (!$this->ip) {
            throw new \InvalidArgumentException("Please set ip");
        }

        if (!$this->type or !in_array($this->type, $this->typeList)) {
            throw new \InvalidArgumentException("Please set type daemon");
        }
    }

    /**
     * @return Process
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }


    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getMaxClient()
    {
        return $this->maxClient;
    }

    /**
     * @param int $maxClient
     */
    public function setMaxClient($maxClient)
    {
        $this->maxClient = $maxClient;
    }



}