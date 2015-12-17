<?php
/**
 * UdpSocket.php
 *
 * @package axiles89\socket\SocketFactory
 * @date: 16.12.2015 20:31
 * @author: Kyshnerev Dmitriy <dimkysh@mail.ru>
 */

namespace axiles89\socket\SocketFactory;


use axiles89\socket\BaseDaemon;

/**
 * Class UdpSocket
 * @package axiles89\socket\SocketFactory
 */
class UdpSocket implements ISocket
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
     * UdpSocket constructor.
     */
    public function __construct(BaseDaemon $handler)
    {
        if (($this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP)) < 0) {
            throw new \Exception('socket_create() failed: '.socket_strerror(socket_last_error())."\n");
        }

        if( !socket_bind($this->socket, $handler->getIp(), $handler->getPort()) ) {
            throw new \Exception('socket_bind() failed: '.socket_strerror(socket_last_error())."\n");
        }

        if (!socket_set_nonblock($this->socket)) {
            throw new \Exception("Unable to set nonblock socket \n");
        }

        $this->handler = $handler;
    }

    /**
     *
     */
    public function read()
    {
        $result = socket_read($this->socket, 6000);
        if ($result) {
            $this->handler->execute($result);
        }
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