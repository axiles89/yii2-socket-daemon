<?php
/**
 * SocketFactory.php
 *
 * @package axiles89\socket
 * @date: 17.12.2015 21:06
 * @author: Kyshnerev Dmitriy <dimkysh@mail.ru>
 */

namespace axiles89\socket;


/**
 * Class SocketFactory
 * @package axiles89\socket
 */
class SocketFactory
{
    /**
     * @var array
     */
    protected static $type = [
        'udp' => "axiles89\\socket\\SocketFactory\\UdpSocket",
        'tcp' => "axiles89\\socket\\SocketFactory\\TcpSocket"
    ];

    /**
     * @param $type
     * @param BaseDaemon $daemon
     * @return mixed
     */
    public static function create($type, BaseDaemon $daemon) {
        if (array_key_exists($type, self::$type)) {
            $model = self::$type[$type];
            return new $model($daemon);
        }
    }
}