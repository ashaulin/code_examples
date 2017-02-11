<?php
require_once(realpath(dirname(__FILE__).'/../SeleniumTests.php'));
class PodpiskaNameEmailPhoneActiv extends SeleniumTests
{
   public function testMyTestCase()
  { $this->login();
    $this->open("/access/logon");
    $this->type("id=user_name-input", "admin");
    $this->type("id=password-input", "1qaz2wsx");
    $this->click("css=input[type=\"submit\"]");
    $this->waitForPageToLoad("30000");

    $this->open("/staffs/access/users");
    $this->waitForPageToLoad("");
    $this->type("name=filter[kw]", "admin");
    $this->click("name=filter[kw_equiv]");
    sleep(5);
    $this->click("name=find");
    sleep(5);
    $this->click("css=i.icon-dotted");
    $this->waitForPageToLoad("30000");
    $this->click("css=i.icon-showed");
    $check = $this->isChecked("name=user_noactivation");
    if ($check == true) {
    $this->click("name=user_noactivation");}
    $this->click("css=input.button-popup");
    $this->type("name=comment", "Vkluchili activaciyu podpiski");
    $this->click("name=save");
    $this->open("/rassilki/");
    $this->click("css=a.button.mid > span.in");
    $this->waitForPageToLoad("30000");
    $this->type("name=rass_name", $this->getEval("Math.random().toString(36).substring(8)"));
    $id_group = $this->getValue("name=rass_name");
    $this->type("name=rass_title", $id_group);
    $this->click("name=save");
    $this->waitForPageToLoad("30000");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isTextPresent("")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->open("/rassilki/code");
    $this->click("css=a.button.mid > span.in");
    $this->waitForPageToLoad("30000");
    $this->click("xpath=(//label[text()=' [$id_group] $id_group']/input)");
    $this->click("css=i.icon-showed");
    $this->click("id=code-phone-field");
    $this->runScript("GetFormCode();");
    $coderesult = $this->getValue("id=code-result");    
    $str = join('\n', split("\n", $coderesult)); 
    $str = join("\'", split("'", $str));   
 
    $this->open("/sites/");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isElementPresent("css=a.site_num.blue")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->click("css=a.site_num.blue");
    $this->waitForPageToLoad("30000");
    $this->click("css=a.button.mid > span.in");
    $this->waitForPageToLoad("30000");
    $this->type("name=content_name", $this->getEval("Math.random().toString(36).substring(8)"));
    $namepage = $this->getValue("name=content_name");
    $this->type("name=variant_title[0]", "testpage");
    sleep(NaN);
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isElementPresent("id=cke_19_label")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->click("id=cke_19_label");
    $this->runScript("CKEDITOR.instances['variant_html[0]'].setData('" . $str . "');");
    sleep(5);
    $this->click("id=cke_19_label");
    sleep(5);
    $this->click("name=save");
    $this->waitForPageToLoad("30000");
    // Активация подписки должна быть включена
    $this->open("/" . $namepage);
    $this->refresh();
    $this->waitForPageToLoad("30000");
    $this->type("name=lead_name", "testsubscribe");
    $this->type("name=lead_email", $this->getEval("Math.random().toString(36).substring(2,6)+'@list.ru'"));
    $lead_email = $this->getValue("name=lead_email");
    $this->type("name=lead_phone", "375291516257");
    sleep(5);
    $this->click("//tr[5]/td/input");
    

    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isTextPresent("")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->open("http://jcstage.co/webmail/src/login.php");
    $this->type("name=login_username", "webmail");
    $this->type("name=secretkey", "justclick");
    $this->click("css=input[type=\"submit\"]");
    $this->waitForPageToLoad("30000");
    $this->selectWindow("name=right");
    $this->click("link=Search");
    $this->waitForPageToLoad("30000");
    $this->type("name=what", $lead_email);
    $this->select("name=where", "label=To");
    $this->click("name=submit");
    $this->waitForPageToLoad("30000");
    sleep(20);
    $this->click("link=Подтвердите, пожалуйста, подписку на «" . $id_group . "»");
    $this->waitForPageToLoad("30000");
    $this->click("css=pre > a");
    $this->assertTrue($this->isTextPresent(""));
    $this->open("/rassilki/");
    $this->click("css=span.in.cw110");
    $this->type("name=filter[kw]", $id_group);
    $this->click("name=find");
    $this->assertTrue($this->isTextPresent(""));
    sleep(2);
    $this->click("css=a.count-report");
    $this->waitForPageToLoad("30000");
    $this->assertEquals($lead_email, $this->getTable("css=div.result > table.standart-view.1.0"));
  }
}
?>