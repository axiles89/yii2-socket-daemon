<?php
/**
 * TestDaemon.php
 *
 * @package axiles89\socket\Test
 * @date: 11.12.2015 14:11
 * @author: Kyshnerev Dmitriy <dimkysh@mail.ru>
 */

namespace axiles89\socket\Test;


use axiles89\socket\BaseDaemon;

/**
 * Class TestDaemon
 * @package axiles89\socket\Test
 */
class TestDaemon extends BaseDaemon
{
    /**
     * @param $data
     */
    public function execute($data) {
        echo "{$data} \n";
    }
}