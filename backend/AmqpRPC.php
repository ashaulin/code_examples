<?php
/**
 * Created by PhpStorm.
 * User: vlyagusha
 * Date: 19.08.15
 * Time: 22:13
 * Скрипт обработки удаленного вызова процедур (RPC)
 * Подробная документация тут
 */

class AmqpRPCAction extends App_Cli_AMQPConsumer
{
    const ERROR_CLASS_EMPTY = 1;
    const ERROR_METHOD_EMPTY = 2;
    const ERROR_CLASS_NOT_EXISTS = 3;
    const ERROR_METHOD_NOT_EXISTS = 4;

    const ERROR_CLASS_EMPTY_TEXT = 'Нет имени класса!';
    const ERROR_METHOD_EMPTY_TEXT = 'Нет имени метода!';
    const ERROR_CLASS_NOT_EXISTS_TEXT = 'Класс не существует!';
    const ERROR_METHOD_NOT_EXISTS_TEXT = 'Метод не существует!';

    public function __construct() {
        parent::__construct('RPC', 'RPC.'.php_uname('n'), 'common');
        $this->callback = array($this, 'operate');
        $this->_logFile = CMS_DOCUMENT_ROOT.'/data/logs/RPC_'.date('Ymd').'.log';
    }

    protected function _writeLog($message, $temp=null) {
        parent::_writeLog($this->_logFile, $message);
    }

    protected function _sendReply($to, $reply) {
        if (isset($reply['error_code'])) {
            $reply['server'] = php_uname('n'); // в случае ошибки добавим имя сервера для удобства отладки
        }
        $AMQP = new App_AMQPProducer($to, 'common', AMQP_EX_TYPE_DIRECT, AMQP_IFUNUSED | AMQP_AUTODELETE | AMQP_DURABLE);
        $AMQP->send(serialize($reply));
    }

    public function operate($message) {
        $message = unserialize($message);

        $server = $message['server']; // сервер, на котором надо выполнить (если пусто, то на всех)
        $reply_to = $message['reply_to']; // куда писать ответ
        $class = $message['class']; // имя источника данных
        $method = $message['method']; // метод в источнике данных
        $input = unserialize($message['input']); // входные данные
        $is_static = isset($message['is_static']) ? $message['is_static'] : false; // по умолчанию вызов нестатичный

        // если явно указали
        if (!empty($server) && $server != php_uname('n')) {
            $this->_writeLog('Прекращена обработка сообщения'.PHP_EOL.var_export($message, true).PHP_EOL.' на сервере '.php_uname('n').PHP_EOL);
            return;
        }

        // проверим обязательные параметры
        if (empty($class)) {
            $this->_sendReply($reply_to, array('error_code' => self::ERROR_CLASS_EMPTY, 'error_message' => self::ERROR_CLASS_EMPTY_TEXT));
            return;
        };

        if (empty($method)) {
            $this->_sendReply($reply_to, array('error_code' => self::ERROR_METHOD_EMPTY, 'error_message' => self::ERROR_METHOD_EMPTY_TEXT));
            return;
        };

        if (!class_exists($class)) {
            $this->_sendReply($reply_to, array('error_code' => self::ERROR_CLASS_NOT_EXISTS, 'error_message' => self::ERROR_CLASS_NOT_EXISTS_TEXT));
            return;
        }

        if (!method_exists($class, $method)) {
            $this->_sendReply($reply_to, array('error_code' => self::ERROR_METHOD_NOT_EXISTS, 'error_message' => self::ERROR_METHOD_NOT_EXISTS_TEXT));
            return;
        }

        // приступаем к обработке данных
        try {
            $params = array();
            for ($i = 0; $i < count($input); $i++) {
                $params[] = '$input['.$i.']';
            }
            if ($is_static) {
                eval('$reply = $class::$method('.implode(', ', $params).');');
            }
            else {
                eval('$object = new $class();');
                eval('$reply = $object->$method('.implode(', ', $params).');');
            }
        }
        catch (Exception $e) {
            $reply = array('error_code' => $e->getCode(), 'error_message' => $e->getMessage());
        }

        // если есть обратный адрес - пошлем ответ
        if (!empty($reply_to)) {
            $this->_sendReply($reply_to, $reply);
        }
    }

}
