<?php
/**
 * ISocket.php
 *
 * @package axiles89\socket\SocketFactory
 * @date: 16.12.2015 20:23
 * @author: Kyshnerev Dmitriy <dimkysh@mail.ru>
 */

namespace axiles89\socket\SocketFactory;


/**
 * Interface ISocket
 * @package axiles89\socket\SocketFactory
 */
interface ISocket
{
    /**
     * @return mixed
     */
    public function read();

    /**
     * @return mixed
     */
    public function close();
}