<?php
/**
 * TcpSocket.php
 *
 * @package axiles89\socket\SocketFactory
 * @date: 25.12.2015 17:50
 * @author: Kyshnerev Dmitriy <dimkysh@mail.ru>
 */

namespace axiles89\socket\SocketFactory;

use axiles89\socket\BaseDaemon;


/**
 * Class TcpSocket
 * @package axiles89\socket\SocketFactory
 */
class TcpSocket implements ISocket
{
    /**
     * @var resource
     */
    protected $socket;

    /**
     * @var BaseDaemon
     */
    protected $handler;

    /**
     * @var array
     */
    protected $read = [];

    /**
     * @var array
     */
    protected $client = [];

    /**
     * UdpSocket constructor.
     */
    public function __construct(BaseDaemon $handler)
    {
        if (($this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
            throw new \Exception('socket_create() failed: '.socket_strerror(socket_last_error())."\n");
        }

        if( !socket_bind($this->socket, $handler->getIp(), $handler->getPort()) ) {
            throw new \Exception('socket_bind() failed: '.socket_strerror(socket_last_error())."\n");
        }

        if (!socket_set_nonblock($this->socket)) {
            throw new \Exception("Unable to set nonblock socket \n");
        }

        if(!socket_listen($this->socket, $handler->getMaxClient())) {
            throw new \Exception('socket_listen() failed: '.socket_strerror(socket_last_error())."\n");
        }

        $this->read[] = $this->socket;
        $this->handler = $handler;
    }

    /**
     *
     */
    public function read()
    {
        $NULL = null;
        $num_changed = socket_select($this->read, $NULL, $NULL, 0);

        if ($num_changed) {
            if (in_array($this->socket, $this->read)) {
                $this->client[] = socket_accept($this->socket);
            }

            foreach ($this->client as $key => $value) {
                if (in_array($value, $this->read)) {
                    $result = socket_read($value, 6000);
                    if ($result) {
                        $this->handler->execute($result);
                    } else {
                        socket_shutdown($value);
                        unset($this->client[$key]);
                    }
                }
            }
        }

        $this->read = $this->client;
        $this->read[] = $this->socket;
    }

    /**
     * @return bool
     */
    public function close()
    {
        socket_close($this->socket);
        return true;
    }

}