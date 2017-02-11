<?php
// Класс используемый в наших функциональных тестах на Селениуме
// Задаёт некоторые стандартные настройки
// и переопределеяет некоторое поведение в классе PHPUnit_Extensions_SeleniumTestCase

require_once('PHPUnit/Extensions/SeleniumTestCase/Autoload.php');

class SeleniumTests extends PHPUnit_Extensions_SeleniumTestCase
{
    public $waitForPageToLoad_timeout = 12000; // Время ожидания загрузки страницы
    public $site = 'jcstage.co'; // домен, на котором запускаются тесты
    public $user_name = 'admin'; // логин пользователя, под которым тестируем
    public $user_pass = '1qaz2wsx'; // пароль пользователя, под которым тестируем

    // стандартный инициализирующий метод для всех селениум-тестов
    protected function setUp() {
        error_reporting(E_ERROR & E_PARSE & E_CORE_ERROR & E_COMPILE_ERROR); // чтобы реагировало только на фатальные ошибки
        $this->setHost('127.0.0.1');
        $this->setPort(4444);
        $this->setBrowser("*firefox");
        $this->setBrowserUrl("http://$this->user_name.$this->site/");
    }

    // логинемся в кабинет пользователя
    public function login()    {
        $this->open("http://$this->site/access/logon");
        $this->waitForPageToLoad();
        try {
            echo "\nLOGIN\n";
            $this->click("id=user_name-label");
            $this->type("id=user_name-input", $user_name);
            $this->type("id=password-input", $user_pass);
            $this->click("css=input[type=\"submit\"]");
        }catch(Exception $e){
        }
        $this->waitForPageToLoad();
    }

    // этот метод не определен в базовом классе, но генерируется в тестах
    // переопределяем его
    protected function sendKeys($elem, $send_keys) {
        $this->type($elem, $send_keys);
    }

    // Не используем значение по умолчанию для ожидания страницы, которое генерирует Selenium IDE
    protected function waitForPageToLoad($timeout=null) {
        // Используем своё значение, определенное в этом классе
        try { // почемуто waitForPageToLoad переодически вылетает - перехватываем, и пробуем продолжить дальше
            parent::waitForPageToLoad($this->waitForPageToLoad_timeout);
        }catch(Exception $e){
        }
    }
}
?>
