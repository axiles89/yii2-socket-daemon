<?php
/**
 * SocketController.php
 *
 * @package axiles89\socket
 * @date: 11.12.2015 13:06
 * @author: Kyshnerev Dmitriy <dimkysh@mail.ru>
 */

namespace axiles89\socket;


use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class SocketController
 * @package axiles89\socket
 */
class SocketController extends Controller
{
    /**
     * @var
     */
    public $socketComponent;

    /**
     * @param int $id
     */
    public function actionStop($id = 0) {
        $component = $this->getApplication($id);
        $daemon = $component->getDaemon();

        $this->stdout("Start stop daemons\n", Console::FG_GREEN);

        foreach ($daemon as $value) {
            $process = $value->getProcess();

            if ($process->isRunning()) {
                $process->stop();
                $this->stdout("Success: Daemon {$value->getName()} is stopped\n", Console::FG_YELLOW);
            } else {
                $this->stdout("Error: Daemon {$value->getName()} is not running\n", Console::FG_RED);
            }
        }
        $this->stdout("End stop daemons\n", Console::FG_GREEN);
    }

    /**
     * @param int $id
     */
    public function actionStart($id = 0) {

        $component = $this->getApplication($id);
        $daemon = $component->getDaemon();

        $this->stdout("Start run daemons\n", Console::FG_GREEN);

        foreach ($daemon as $value) {
            $process = $value->getProcess();

            if ($process->isRunning()) {
                $component->deleteDaemon($value->getName());
                $this->stdout("Error: Daemon {$value->getName()} is already running\n", Console::FG_RED);
            } else {
                $this->stdout("Success: Daemon {$value->getName()} is running\n", Console::FG_YELLOW);
            }
        }

        $component->run();
        $this->stdout("End run daemons\n", Console::FG_GREEN);
    }

    /**
     * @param $id
     * @return mixed
     */
    private function getApplication($id) {
        $component = \Yii::$app->get($this->socketComponent);
        return $component->getApplication($id);
    }
}