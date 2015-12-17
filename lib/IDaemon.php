<?php
/**
 * IDaemon.php
 *
 * @package axiles89\socket
 * @date: 11.12.2015 14:08
 * @author: Kyshnerev Dmitriy <dimkysh@mail.ru>
 */

namespace axiles89\socket;


/**
 * Interface IDaemon
 * @package axiles89\socket
 */
interface IDaemon
{
    /**
     * @param $data
     * @return mixed
     */
    public function execute($data);
}