Socket daemon starter
============================

Библиотека для запуска обработчиков сокетов (UDP, TCP). 

Установка
-------------------
 
Установка с помощью пакета composer `"axiles89/yii2-socket": "*"`


Пример использования
------------

### Yii2

Сконфигурируйте `controllerMap` и установите нужные обработчики (тестовый обработчик `TestDaemon`):

```php
    'controllerMap' => [
      'socket' => [
          'class' => 'axiles89\socket\SocketController',
          'socketComponent' => 'socket'
      ]
    ],
    'components' => [
      'socket' => [
          'class' => 'axiles89\socket\SocketComponent',
          'user' => 'www-data',
          'daemon' => [
              'TestDaemon' => [
                  'class' => 'axiles89\socket\test\TestDaemon',
                  'type' => 'udp',
                  'ip' => '192.168.68.130',
                  'port' => 4040
              ],
              'TestDaemon2' => [
                  'class' => 'axiles89\socket\test\TestDaemon',
                  'type' => 'tcp',
                  'ip' => '192.168.68.130',
                  'port' => 4141,
                  'maxClient' => 20
              ],
              ...
            ]
        ],
    ]
```

Для написания собственного обработчика нужно наследовать свой класс от `\axiles89\socket\BaseDaemon`, реализовав метод `execute`,
в который передается параметр с прочитанными с нужного сокета данными:

```
  class TestDaemon extends BaseDaemon
  {
      /**
       * @param $data
       */
      public function execute($data) {
        ...
      }
  }
```

Запустить и остановить можно как все сокеты, так и отдельный сокет с определенным именем:

```
  php yii socket/start
  php yii socket/start AddDaemon
  ...
  php yii socket/stop
  php yii socket/stop AddDaemon
```
