<?php

include 'InitUnitTest.php';
include CMS_LIB_PATH.'WebDriver/__init__.php';

class InitFuncTest extends InitUnitTest
{
    protected $driver;

    protected function setUp() {
        $this->driver = RemoteWebDriver::create(
            'http://localhost:4444/wd/hub'
            , array(
                WebDriverCapabilityType::BROWSER_NAME => WebDriverBrowserType::FIREFOX
            )
        );
    }
  
    protected function tearDown() {
        $this->driver->quit();
    }
}    
