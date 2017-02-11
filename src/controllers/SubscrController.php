<?php
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

require_once('Functions.php');
require_once('Shop/Links.php');

class SubscrController extends App_Controller {
    private $sbscrber;

    public function __construct()
    {
        parent::__construct();

        if($this->getAction()=='indexAction') {
        // это переход по ссылке "Изменить данные" из письма, он обрабатывается в indexAction()
            $this->template_name = 'no_layout';
            return;
        }

        $this->template_name = 'subsber_layout';
        $this->title = ' :: ' . URL::baseDomain();
        $this->addStyle('sbscrber.css');
        $lead_id = DecodeInteger($_GET['sid']);
        $link_crc = $_GET['crc'];

        if (empty($link_crc) || empty($lead_id)) {
            if (empty($_COOKIE['sbscrber'])) {
                throw new AccessDeniedException;
            }
            else {
                list($lead_id, $link_crc) = explode('-', $_COOKIE['sbscrber']);
            }
        }
        else {
            setcookie('sbscrber', $lead_id . '-' . $link_crc, -1, '/');
        }

        if ($link_crc != ShopLinks::subscriberCRC($lead_id))
            throw new AccessDeniedException;

        $mDbLI = new model_Db_LeadInfo();
        $this->sbscrber = $mDbLI->getDataByLeadIdWithAnyOwner($lead_id);
        $this->lead_name = $this->sbscrber['lead_name'];
    }

    private function canAccess() {
        if ($this->uri[1] == 'deleted')
            // удалили аккаунт
            return 30001;

        if (empty($this->sbscrber) || !empty($this->sbscrber['lead_deleted']))
            return 30002;

        return true;
    }

    public function indexAction() {
        $this->show();
        // просто показываем сообщение из шаблона (view), что такой вход теперь не работает
    }


    public function inAction() {
        if (($err = $this->canAccess()) !== true) {
            return $this->error($err);
        }

        $this->title_page = 'Входящие для '.$this->sbscrber['lead_name'].' &lt;'.$this->sbscrber['lead_email'].'&gt;';
        $this->title = $this->title_page.$this->title;
        $this->addScript('content.js');
        $this->show();

    }

    public function inajaxAction() {
        if (($err = $this->canAccess()) !== true) {
            return $this->error($err);
        }

        $this->template_name = 'ajax_layout';
        $page = (!empty($_GET['p'])) ? (int) $_GET['p'] : 1;
        $onPage = (!empty($_GET['per'])) ? (int) $_GET['per'] : 10;

        $mDBSerial = new model_Db_Serial();
        $mDBAnons = new model_Db_Anons();
        $mDBLeadSerial = new model_Db_LeadSerial();
        $mDBLeadAnons = new model_Db_LeadAnons();
        $mDBLeadMail = new model_Db_LeadMail();
        $mDBLeadMailActivation = new model_Db_LeadMailActivation();
        $mDBRassilkiAnons = new model_Db_RassilkiAnons();
        $mTemplate = new Messages_Templates();

        // получаем все письма для этого пользователя
        $mail_mls = $mDBLeadMail->GetAllByLead($this->sbscrber['lead_id']);
        // получаем все письма для этого пользователя
        $mail_mls = array_merge($mail_mls, $mDBLeadMailActivation->GetAllByLead($this->sbscrber['lead_id']));
        // получаем все письма для этого пользователя
        $mail_mls = array_merge($mail_mls, $mDBLeadAnons->GetAllByLead($this->sbscrber['lead_id']));
        // получаем все письма для этого пользователя
        $mail_mls = array_merge($mail_mls, $mDBLeadSerial->GetAllByLead($this->sbscrber['lead_id']));
        // соритуем по дате письма
        function cmp($a, $b) {
            if ($a['time'] == $b['time']) {
                return 0;
            }
            return ($a['time'] > $b['time']) ? -1 : 1;
        }
        usort($mail_mls, "cmp");
        $line = ($page-1) * $onPage;
        for($i=$line; $i<$line+$onPage; $i++) { // обрабатываем только нужное количество писем
            switch($mail_mls[$i]['type']) {
                case 's': // авто-рассылка
                    $serial_ml = $mDBSerial->GetMail($mail_mls[$i]['variant']);
                    // заполняем недостающие поля имени и имейла отправителя
                    $this->GetFromEmail($mail_mls[$i]['from_email'], $mail_mls[$i]['from_name'], $serial_ml['owner_id'], $serial_ml['rass_id']);
                    $mail_mls[$i]['title'] = str_replace('{$name}', $this->sbscrber['lead_name'], $serial_ml['serial_subject']);
                    $mail_mls[$i]['view_link'] = "s/{$serial_ml['serial_id']}/{$mail_mls[$i]['time']}/{$mail_mls[$i]['variant']}";
                break;
                case 'a': // разовое письмо
                    $anons_ml = $mDBAnons->GetMail($mail_mls[$i]['mail_id'], $mail_mls[$i]['variant']);
                    $rass_list_anons = $mDBRassilkiAnons->FindRassByAnons( $mail_mls[$i]['mail_id']);
                    $anons_ml['rass_id'] = false;
                    if( !empty( $rass_list_anons)){
                        $mRassLead = new model_Db_RassilkiLeads();
                        foreach( $rass_list_anons as $rass) {
                            $rs2 = $mRassLead->GetRowById( $rass[ 'rass_id'], $this->sbscrber[ 'lead_id']);
                            if( !empty( $rs2)) {
                                $anons_ml['rass_id'] = $rs2[ 'rass_id'];
                                continue;
                            }
                        }
                    }
                    // заполняем недостающие поля имени и имейла отправителя
                    $this->GetFromEmail($mail_mls[$i]['from_email'], $mail_mls[$i]['from_name'],  $anons_ml['owner_id'], $anons_ml['rass_id']);
                    $mail_mls[$i]['title'] = str_replace('{$name}', $this->sbscrber['lead_name'], $anons_ml['anons_subject']);
                    $mail_mls[$i]['view_link'] = "a/{$mail_mls[$i]['mail_id']}/{$mail_mls[$i]['time']}/{$mail_mls[$i]['variant']}";
                break;
                case 'v': // письмо активации
                    $act_ml = $mDBLeadMailActivation->GetMail($mail_mls[$i]['mail_id']);
                    $rass_ids = explode(',', $act_ml['activation_data']);
                    $act_code = array_shift($rass_ids); // первый элемент - ссылка на активацию, удаляем её
                    // заполняем недостающие поля имени и имейла отправителя и названия рассылки
                    $mail_from_email = false;
                    $mail_from_name = false;
                    $rassilki_title = array();
                    foreach ($rass_ids as $rass_id) {
                        $rass = model_Rassilki::GetByID($rass_id);
                        array_push($rassilki_title, $rass['rass_title']);
                        if (empty($mail_from_email))
                            $mail_from_email = $rass['rass_from_email'];
                        if (empty($mail_from_name))
                            $mail_from_name = $rass['rass_from_name'];
                    }
                    if (empty($mail_from_email))
                        $this->GetFromEmail($mail_from_email, $tmp, $rass['owner_id'], $rass_ids[0]);
                    if (empty($mail_from_name))
                        $this->GetFromEmail($tmp, $mail_from_name, $rass['owner_id'], $rass_ids[0]);
                    $mail_mls[$i]['from_name'] = $mail_from_name;
                    $mail_mls[$i]['from_email'] = $mail_from_email;
                    $mail_mls[$i]['opened'] = $act_ml['message_opened'];
                    $mail_mls[$i]['title'] = $mTemplate->get(Messages_Templates::TYPE_SUBSCRIBE_SUBJECT, NULL, array(
                                                    '$rassilki_title' => join(', ', $rassilki_title),
                                                ));
                    $mail_mls[$i]['view_link'] = "v/{$act_ml['mail_id']}/{$message_time}";
                break;
                case 'e': // прочие письма
                    $mail_ml = $mDBLeadMail->GetMail($mail_mls[$i]['mail_id']);
                    $mail_mls[$i]['from_name'] = $mail_ml['message_from_name'];
                    $mail_mls[$i]['from_email'] = $mail_ml['message_from'];
                    $mail_mls[$i]['opened'] = $mail_ml['message_opened'];
                    $mail_mls[$i]['title'] = $mail_ml['message_subject'];
                    $mail_mls[$i]['view_link'] = "e/{$mail_ml['mail_id']}/{$mail_ml['message_time']}";
                break;
            }
        }
        $paginator = Zend_Paginator::factory($mail_mls);
        $paginator->setCurrentPageNumber($page)
                    ->setItemCountPerPage($onPage);
        $this->_set_body_params = array(
            'items'             => $paginator->getCurrentItems(),
            'pagination'        => $paginator->getPages(),
            'sbscrber_id'         => EncodeInteger($this->sbscrber['lead_id']),
            'sbscrber_crc'        => ShopLinks::subscriberCRC($this->sbscrber['lead_id']),
        );
        $this->show();
    }

    public function viewemailAction() {
        if (($err = $this->canAccess()) !== true) {
            return $this->error($err);
        }

        $this->title_page = 'Письмо для '.$this->sbscrber['lead_name'].' &lt;'.$this->sbscrber['lead_email'].'&gt;';
        $this->title = $this->title_page.$this->title;
        $letter_type         = $this->uri[2];
        $letter_id             = $this->uri[3];
        $letter_time         = $this->uri[4];
        $letter_variant_id     = $this->uri[5];

        $mDBSerial = new model_Db_Serial();
        $mDBAnons = new model_Db_Anons();
        $mLeadSerial = new model_LeadSerial();
        $mLeadAnons = new model_LeadAnons();
        $mDBLeadMail = new model_Db_LeadMail();
        $mDBLeadMailActivation = new model_Db_LeadMailActivation();

        $mDbLeadUnsub = new model_Db_LeadUnsub();
        $mDbRassilkiAnons = new model_Db_RassilkiAnons();

        $mSerial = new model_Serial();
        $mRassilkiAnons = new model_RassilkiAnons();

        $mail = array();
        $mail['time'] = HumenDateTime($letter_time);
        switch($letter_type) {
            case 's': // авто-рассылка
                $letter = $mDBSerial->GetMail($letter_variant_id);

                // ставим метку, что письмо открыто
                $mLeadSerial::Update($letter_id, $this->sbscrber['lead_id'], array('message_status' => 'view', 'message_opened' => true), $letter['owner_id']);
                // Если вдруг пользователь на контроле активности - вычеркнем его
                App_UnsubInactivity::activeLead($this->sbscrber['lead_id'], $letter['rass_id']);

                // пишем активность
                App_Activity::SetView($letter_id, $this->sbscrber['lead_id'], App_Activity::MAIL_TYPE_SERIAL);

                App_Mailer::SerialPrepare($this->sbscrber, $letter, false);
                $mail['from_email'] = App_Mailer::GetFrom();
                $mail['from_name'] = App_Mailer::GetFromName();
                $mail['title'] = App_Mailer::GetSubject();
                $mail['message'] = $letter['serial_format'] == 'html' ? App_Mailer::GetBody() : '<pre>'.App_Mailer::GetBody().'</pre>';
                break;
            case 'a': // разовое письмо
                $letter = $mDBAnons->GetMail($letter_id,  $letter_variant_id);

                // ставим метку, что письмо открыто
                $mLeadAnons->Update($letter_id, $this->sbscrber['lead_id'], array('message_status' => 'view', 'message_opened' => true), $letter['owner_id']);

                // Если вдруг пользователь на контроле активности - вычеркнем его
                // Но сначала нужно узнать в каких рассылках он только что проявил активность
                $rass_ids = $mDbRassilkiAnons->GetAllRassByAnons($letter_id);
                App_UnsubInactivity::activeLead($this->sbscrber['lead_id'], $rass_ids);

                // пишем активность
                App_Activity::SetView($letter_id, $this->sbscrber['lead_id'], App_Activity::MAIL_TYPE_ANONS);

                $mDBRassilkiAnons = new model_Db_RassilkiAnons();
                $letter['rass_list'] = array();
                $mDBRassilkiAnons = new model_Db_RassilkiAnons();
                $rass_list_anons = $mDBRassilkiAnons->FindRassByAnons( $letter_id);
                if( !empty( $rass_list_anons)){
                    $mRassLead = new model_Db_RassilkiLeads();
                    foreach( $rass_list_anons as $rass) {
                        $rs2 = $mRassLead->GetRowById( $rass[ 'rass_id'], $this->sbscrber[ 'lead_id']);
                        if( !empty( $rs2)) {
                            $letter[ 'rass_list'][] = $rs2[ 'rass_id'];
                            continue;
                        }
                    }
                }

                App_Mailer::AnonsPrepare($this->sbscrber, $letter, false);
                $mail['from_email'] = App_Mailer::GetFrom();
                $mail['from_name'] = App_Mailer::GetFromName();
                $mail['title'] = App_Mailer::GetSubject();
                $mail['message'] = $letter['anons_format'] == 'html' ? App_Mailer::GetBody() : '<pre>'.App_Mailer::GetBody().'</pre>';
                break;
            case 'v': // письма активации
                $letter = $mDBLeadMailActivation->GetMail($letter_id);
                // ставим метку, что письмо открыто
                $mDBLeadMailActivation->update(array('message_opened' => true), 'mail_id='.$letter_id);

                $rass_ids = explode(',', $letter['activation_data']);
                $act_code = array_shift($rass_ids); // первый элемент - ссылка на активацию, удаляем её
                // получаем данные для формирования письма активации
                $RASS_RS = array();
                foreach ($rass_ids as $rass_id) {
                    $rass = model_Rassilki::GetByID($rass_id);
                    array_push($RASS_RS, $rass);
                }

                $lead_data['lead_name'] =  $this->sbscrber['lead_name'];
                $lead_data['lead_email'] =  $this->sbscrber['lead_email'];

                $owner_id = ShopUser::GetByID($rass['owner_id']);
                $owner_id['user_domain'] = ShopUser::UserDomain($rass['owner_id']);

                $act_link_url = URL::get($owner_id['user_domain']) .'/link/'.$act_code;
                $act_link_url = '<a href="'.$act_link_url.'">'.$act_link_url.'</a>';

                App_Mailer::ActivatePrepare($owner_id, $rass_ids, $lead_data, $RASS_RS, $act_link_url);
                $mail['from_email'] = App_Mailer::GetFrom();
                $mail['from_name'] = App_Mailer::GetFromName();
                $mail['title'] = App_Mailer::GetSubject();
                $mail['message'] = '<pre>'.App_Mailer::GetBody().'</pre>';
                break;
            case 'e': // прочие письма
                $letter = $mDBLeadMail->GetMail($letter_id);
                // ставим метку, что письмо открыто
                $mDBLeadMail->update(array('message_opened' => true), 'mail_id='.$letter_id);

                $mail['from_email'] = $letter['message_from'];
                $mail['from_name'] = $letter['message_from_name'];
                $mail['title'] = $letter['message_subject'];
                $mail['message'] = stristr($letter['message_body'], '<html>')? $letter['message_body'] : '<pre>'.$letter['message_body'].'</pre>';
                break;
        }

        $this->_set_body_params = array(
            'item' => $mail,
        );
        $this->show();
    }

    private function GetFromEmail(&$from_email, &$from_name, $owner_id, $rass_id) {
        if(empty($from_email) || empty($from_name)) {
            $mDBRass = new model_Db_Rassilki();
            $rass_rs = $mDBRass->GetById($owner_id, $rass_id);
            if(empty($from_email) && !empty($rass_rs['rass_from_email']))
                $from_email = $rass_rs['rass_from_email'];
            if(empty($from_name) && !empty($rass_rs['rass_from_name']))
                $from_name = $rass_rs['rass_from_name'];
        }
        if(empty($from_email) || empty($from_name)) {
            $mDbConfig = new model_Db_Config();
            $config = $mDbConfig->GetById($owner_id);
            if(empty($from_email) && !empty($config['mail_from_email']))
                $from_email = $config['mail_from_email'];
            if(empty($from_name) && !empty($config['mail_from_name']))
                $from_name = $config['mail_from_name'];
        }
    }

    public function profileAction() {
        if (($err = $this->canAccess()) !== true) {
            return $this->error($err);
        }

        $this->title_page = 'Настройки профиля';
        $this->title = $this->title_page.$this->title;
        if(count($_POST)) {
            $mLeads = new model_Leads();
            $mLeads->Update($this->sbscrber['lead_id'], $_POST);
            $this->sbscrber = $mLeads->GetById($this->sbscrber['lead_id']);
        }
        $this->_set_body_params = array(
            'items' => $this->sbscrber,
        );
        $this->show();
    }

    public function deleteAction() {
        if (($err = $this->canAccess()) !== true) {
            return $this->error($err);
        }

        $this->title_page = 'Удаление аккаунта';
        $this->title = $this->title_page.$this->title;
        $action = 'exlam';
        if(count($_POST)) {
            if(!empty($_POST['delete'])) {
                $AMQP = new App_AMQPProducer('lead.delete', 'common');
                $AMQP->send($this->sbscrber['lead_email']);
                header('location: /'.$this->uri[0].'/deleted');
                return;
            }
            else
                $action = 'not';
        }

        $this->_set_body_params = array(
            'action' => $action,
        );
        $this->show();
    }

    public function rassilkiAction() {
        if (($err = $this->canAccess()) !== true) {
            return $this->error($err);
        }

        $this->title_page = 'Список рассылок';
        $this->title = $this->title_page.$this->title;
        $this->addScript('content.js');


        if (!empty($_GET['act']) && (!empty($_GET['category_id']) || !empty($_GET['owner_id']))) {
            $rassIds = [];
            if (!empty($_GET['category_id'])) {
                $rassIds = $this->getRassByCategory(DecodeInteger($_GET['category_id']));
            } elseif (!empty($_GET['owner_id'])) {
                $mDBRL = new model_Db_RassilkiLeads();
                $rassIds = $mDBRL->GetLeadRassByOwner($this->sbscrber['lead_id'], DecodeInteger($_GET['owner_id']));
            }
            if ($_GET['act'] == 'delete') {
                foreach ($rassIds as $rassId) {
                    App_Leads::Unsubscribe($rassId, $this->sbscrber['lead_id']);
                }
            } elseif ($_GET['act'] == 'add' && !empty($_GET['category_id'])) { //подписаться можно только на конкретную категорию
                foreach ($rassIds as $rassId) {
                    App_Leads::Subscribe($rassId, array(
                        'email' => $this->sbscrber['lead_email'],
                        'name' => $this->sbscrber['lead_name'],
                        'phone' => $this->sbscrber['lead_phone'],
                        'city' => $this->sbscrber['lead_city'],
                    ), array(
                        'lead_status' => model_Db_LeadInfo::RASSILKI_STATUS_SUBSCRIBE,
                        'active' => true,
                        'noaff' => true,
                    ), $errors);
                }
            }
        }

        $this->show();
    }

    public function rassilkiajaxAction() {
        if (($err = $this->canAccess()) !== true) {
            return $this->error($err);
        }

        $this->template_name = 'ajax_layout';
        $page = (!empty($_GET['p'])) ? (int) $_GET['p'] : 1;
        $onPage = (!empty($_GET['per'])) ? (int) $_GET['per'] : 20;

        $mDBRassilkiLeads = new model_Db_RassilkiLeads();
        $mDbRassilkiGroup = new model_Db_RassilkiGroup();
        $tmpRass = $mDBRassilkiLeads->GetByLead($this->sbscrber['lead_id']);

        $groups = [];   //Группы владельцев рассылок
        $urs = [];      //Владельцы рассылок
        $rass = [];     //Рассылки по категориям
        foreach ($tmpRass as $k=>$v) {
            //Выбираем владельца рассылки, если еще не выбрали
            if (empty($urs[$v['owner_id']])) {
                $urs[$v['owner_id']] = ShopUser::GetByID($v['owner_id']);
            }
            //Выбираем категории владельца рассылки, если еще не выбрали
            if (empty($groups[$v['owner_id']])) {
                $groups[$v['owner_id']] = $mDbRassilkiGroup->GetGroups($v['owner_id']);
                $rass[$v['owner_id'].'_alljcrassilki'] = [
                    'active' => 1,
                    'owner_id' => $v['owner_id'],
                    'group_title' => 'Все рассылки автора',
                    'user_full_name' => $urs[$v['owner_id']]['user_full_name'],
                ];
            }
            //Если есть категории, то находим к какой категории относится рассылка
            if (!empty($groups[$v['owner_id']])) {
                $inGroup = [];
                foreach ($groups[$v['owner_id']] as $group) {
                    $groupRassilki = explode(',', $group['group_rassilki']);
                    if (in_array($v['rass_id'], $groupRassilki)) {
                        $inGroup = $group;
                        break;
                    }
                }
                // #3768: показываем только группы с установленными категориями
                if (!empty($inGroup)) {
                    if (!isset($rass[$v['owner_id'].'_'.$inGroup['group_id']]['active'])) {
                        $rass[$v['owner_id'].'_'.$inGroup['group_id']]['active'] = 0;
                    }
                    if ($v['lead_active'] == 1) {
                        $rass[$v['owner_id'].'_'.$inGroup['group_id']]['active']++;
                    }
                    $rass[$v['owner_id'].'_'.$inGroup['group_id']]['category_id'] = $inGroup['group_id'];
                    $rass[$v['owner_id'].'_'.$inGroup['group_id']]['rassilki'][] = $v;
                    $rass[$v['owner_id'].'_'.$inGroup['group_id']]['group_title'] = $inGroup['group_title'];
                    $rass[$v['owner_id'].'_'.$inGroup['group_id']]['user_full_name'] = ''; // #3768: имя автора выводим 1 раз только в итоговой строчке
                }
            }
        }

        $paginator = Zend_Paginator::factory($rass);
        $paginator->setCurrentPageNumber($page)
                    ->setItemCountPerPage($onPage);

        $this->_set_body_params = array(
            'items'             => $paginator->getCurrentItems(),
            'pagination'        => $paginator->getPages(),
            'sbscrber_id'         => EncodeInteger($this->sbscrber['lead_id']),
            'sbscrber_crc'        => ShopLinks::subscriberCRC($this->sbscrber['lead_id']),
        );
        $this->show();
    }

    private function getRassByCategory($categoryId)
    {
        $rassIds = [];
        $mDBRassilkiLeads = new model_Db_RassilkiLeads();
        $mDbRassilkiGroup = new model_Db_RassilkiGroup();
        $leadRass = $mDBRassilkiLeads->GetByLead($this->sbscrber['lead_id']);
        $category = $mDbRassilkiGroup->GetGroup($categoryId);
        $categoryRass = explode(',', $category['group_rassilki']);

        if (empty($category) || empty($categoryRass)) {
            return $rassIds;
        }

        foreach ($leadRass as $rass) {
            if (in_array($rass['rass_id'], $categoryRass)) {
                $rassIds[] = $rass['rass_id'];
            }
        }

        return $rassIds;
    }
}
