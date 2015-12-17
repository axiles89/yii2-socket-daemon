<?php
/**
 * SocketComponent.php
 *
 * @package axiles89\socket
 * @date: 11.12.2015 13:29
 * @author: Kyshnerev Dmitriy <dimkysh@mail.ru>
 */

namespace axiles89\socket;


use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class SocketComponent
 * @package axiles89\socket
 */
class SocketComponent extends Component
{
    /**
     * @var array
     */
    public $daemon = [];

    /**
     * @var string
     */
    public $user = "www-data";


    /**
     * @param $nameDaemon
     * @return Application
     * @throws InvalidConfigException
     */
    public function getApplication($nameDaemon) {

        $application = new Application();

        foreach ($this->daemon as $key => $value) {

            if ($nameDaemon != '' and $key != $nameDaemon) {
                continue;
            }

            $daemon = \Yii::createObject($value, [new Process($key)]);

            if (!($daemon instanceof IDaemon)) {
                throw new InvalidConfigException("Daemon must be instance of IDaemon.");
            }

            $daemon->setName($key);
            $application->addDaemon($daemon);
        }

        $application->setUser($this->user);

        return $application;
    }
}