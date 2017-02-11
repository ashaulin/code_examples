<?php
class ApiController extends App_Controller {

    private $selfActivateService; // массив для систем с привелегиями привилегиями (задаётся в __construct)

    const NO_ERROR = 0;
    const NO_ERROR_TEXT = 'OK';
    const ERROR_NO_HASH = 1;
    const ERROR_NO_HASH_TEXT = "Not transferred hash";
    const ERROR_MISSING_PARAM = 2;
    const ERROR_MISSING_PARAM_TEXT = "No transmitted data";
    const ERROR_WRONG_DATA = 3;
    const ERROR_WRONG_DATA_TEXT = "Wrong posted data";
    const ERROR_HASH_MISMATCH = 4;
    const ERROR_HASH_MISMATCH_TEXT = "Incorrect hash";
    const ERROR_USER_INVALID = 5;
    const ERROR_USER_INVALID_TEXT = 'Invalid user name';
    const ERROR_IP_INVALID = 6;
    const ERROR_IP_INVALID_TEXT = 'Permission denied for IP ';
    const ERROR_USER_DESABLED = 7;
    const ERROR_USER_DESABLED_TEXT = 'Account disabled';
    const ERROR_IP_BLOCK = 8;
    const ERROR_IP_BLOCK_TEXT = 'Limit to subscribe for this IP exided. Try later.';
    // со 100 начинаются ошибки подписки
    const NO_ERROR_ACTIVATION_SEND = "Activation email sent to subscriber";
    const NO_ERROR_NO_ACTIVATION = "Subscriber added. Activation email not sent.";
    const ERROR_MISSED_EMAIL = 100;
    const ERROR_MISSED_EMAIL_TEXT = "Email is missing";
    const ERROR_SUBSCRIBE = 101;
    const ERROR_SUBSCRIBE_TEXT = "Subscription error: ";
    const ERROR_ALREADY_REGISTERED = 102;
    const ERROR_ALREADY_REGISTERED_TEXT = "The subscriber is already registered";
    const ERROR_GROUP_INVALID = 103;
    const ERROR_GROUP_INVALID_TEXT = 'Has an invalid subscriptions group';
    const ERROR_RASS_CANNOT_SUBS = 104;
    const ERROR_RASS_CANNOT_SUBS_TEXT = "Subscription forbidden for ";
    const ERROR_RASS_CANNOT_SUBSCRIBE = 105;
    const ERROR_RASS_CANNOT_SUBSCRIBE_TEXT = "The subscriber cannot be subscribed";
    const ERROR_RASS_CANNOT_ACTIVATE = 106;
    const ERROR_RASS_CANNOT_ACTIVATE_TEXT = "The subscriber cannot be activated";
    // с 200 начинаются ошибки изменения статуса заказа и добавления, удаления заказа
    const NO_ERROR_STATUS_CHANGED = "Order status changed";
    const ERROR_NONEXISTENT = 200;
    const ERROR_NONEXISTENT_TEXT = "Nonexistent order";
    const ERROR_WRONG_STATUS = 201;
    const ERROR_WRONG_STATUS_TEXT = "Wrong status";
    const ERROR_NOT_PAID = 202;
    const ERROR_NOT_PAID_TEXT = "Order not paid";
    const ERROR_ORDER_EMPTY = 203;
    const ERROR_ORDER_EMPTY_TEXT = "Order number is empty";
    const ERROR_ORDER_STATUS_NOT_CHANGED = 204;
    const ERROR_ORDER_STATUS_NOT_CHANGED_TEXT = "Order status is not changed";
    const ERROR_REFUND_FAIL = 205;
    const ERROR_REFUND_FAIL_TEXT = "Refund error:";
    // с 300 начинаются ошибки создания/изменения/удаления товаров
    const ERROR_PRODUCT_NOTFOUND = 300;
    const ERROR_PRODUCT_NOTFOUND_TEXT = "Product not found";
    const ERROR_ALREADY_PRODUCT = 301;
    const ERROR_ALREADY_PRODUCT_TEXT = "Product already exist";
    const ERROR_NOTREMOVE_PRODUCT = 302;
    const ERROR_NOTREMOVE_PRODUCT_TEXT = "Failed to remove product";
    const ERROR_NOTADD_PRODUCT = 303;
    const ERROR_NOTADD_PRODUCT_TEXT = "Failed to add product";
    // с 400 начинаются ошибки получения списка купленных продуктов по мейлу клиента, а также ошибки получения информации заказа по ID
    const ERROR_ORDER_NOTFOUND = 400;
    const ERROR_ORDER_NOTFOUND_TEXT = "Order not found";
    const ERROR_ORDER_NO_ORDERS = 401;
    const ERROR_ORDER_NO_ORDERS_TEXT = "Orders not found";
    // с 500 начинаются ошибки получения списка групп подписчиков по email-у клиента
    const ERROR_SUBSCRIBER_NOTFOUND = 500;
    const ERROR_SUBSCRIBER_NOTFOUND_TEXT = "Subscriber not found";
    const ERROR_GROUP_NOTFOUND = 501;
    const ERROR_GROUP_NOTFOUND_TEXT = "Group not found";
    // с 600 начинаются ошибки создания заказа
    const NO_ERROR_ORDER_CREATED = "Order created";
    const ERROR_WRONG_EMAIL = 600;
    const ERROR_WRONG_EMAIL_TEXT = "Wrong email: '";
    const ERROR_ORDER_ALREADY = 601;
    const ERROR_ORDER_ALREADY_TEXT = "Order already exist. His number send in result array.";
    const ERROR_ORDER_CREATED = 602;
    const ERROR_ORDER_CREATED_TEXT = "Error creating order";
    const ERROR_ORDER_NO_PRODUCTS = 603;
    const ERROR_ORDER_NO_PRODUCTS_TEXT = "Missing products";
    const ERROR_ORDER_NO_GOOD = 604;
    const ERROR_ORDER_NO_GOOD_TEXT = "Product not exist: ";
    const ERROR_ORDER_NO_DELIVERY = 605;
    const ERROR_ORDER_NO_DELIVERY_TEXT = "Not having any data for delivery products";
    const ERROR_ORDER_API_DISABLED = 606;
    const ERROR_ORDER_API_DISABLED_TEXT = "Function for creating new orders throw API was disabled for your account";
    // с 700 начинаются ошибки получения всех продуктов
    const ERROR_NO_PRODUCTS = 700;
    const ERROR_NO_PRODUCTS_TEXT = "No products";
    // с 800 начинаются ошибки удаления подписчика
    const ERROR_GROUP_NOT_FOUND = 800;
    const ERROR_GROUP_NOT_FOUND_TEXT = "Group subscribers is not found";
    const ERROR_SUBSCRIBE_ADDRESS_NOT_FOUND = 801;
    const ERROR_SUBSCRIBE_ADDRESS_NOT_FOUND_TEXT = "Subscriber with such address is not found";
    // с 900 начинаются ошибки при работе с доменами пользователя
    const ERROR_ACTIVE_DOMAINS_NOTFOUND = 900;
    const ERROR_ACTIVE_DOMAINS_NOTFOUND_TEXT = "Active domains not found";
    const ERROR_CANCEL_RECURRENT = 950;
    const ERROR_CANCEL_RECURRENT_TEXT = "Recurrent is not canceled";
    // с 1000 начинаются ошибки при работе с партнерами
    const ERROR_PARTNER_NOTFOUND = 1000;
    const ERROR_PARTNER_NOTFOUND_TEXT = "Partner not found";
    
    public function __construct() {
        parent::__construct();
        $this->GetUserInfo();
        $this->template_name = 'api_layout';
        $this->_loadModel( 'Db_IpBlock');
        $this->selfActivateService = array( 'KursBesplatno', 'justclick' );
    }

    public function GetGroupsListAction() {
        if ( !$this->CommonChecks()) 
            return;

        $owner_id    = $this->user_rs['user_id'];
        $this->_loadModel( 'Db_Rassilki');
        $this->_loadModel( 'Db_RassilkiGroup');
        $mailings    = $this->_model->Db_Rassilki->GetByUserId( $owner_id);
        $mailGroups    = $this->_model->Db_RassilkiGroup->GetGroups( $owner_id);
        $aMailGroups= array();

        foreach( $mailGroups as $row ) {
            $aMailGroups[ $row[ 'group_id'] ] = array(
                'id'        => $row[ 'group_id'],
                'title'        => $row[ 'group_title'],
                'mailings'    => explode( ',', $row[ 'group_rassilki'] )
            );
        }

        $aMailings = array();
        $i = 0;

        foreach( $mailings as $row ) {
            // пропускаем автогруппы, на них нельзя подписаться
            if( intval( $row['rass_can_subscribe'] ) == 2 ) 
                continue;

            $aMailGroups = $this->__groupMailing( $aMailGroups, $row );
        }

        $aMailGroups = $this->__cleanMailGroups( $aMailGroups );

        $this->GenerateResponseResult( self::NO_ERROR, self::NO_ERROR_TEXT, $aMailGroups );
    }

    private function __groupMailing( $aMailGroups, $mailing ) {
        $item = array(
            'id'    => $mailing['rass_id'],
            'title' => ( $mailing['rass_title'] ) ? $mailing['rass_title'] : $mailing['rass_name'],
            'name'  => $mailing['rass_name'],
        );

        foreach( $aMailGroups as $id => $group ) {
            if( in_array( $mailing['rass_id'], $group['mailings'] ) ) {
                if( !isset( $aMailGroups[$id]['sub'] ) ) {
                    $aMailGroups[$id]['sub'] = array();
                }

                $aMailGroups[$id]['sub'][] = $item;

                return $aMailGroups;
            }
        }

        if( !isset( $aMailGroups['Other'] ) ) {
            $aMailGroups['Other'] = array(
                'id'        => 'other',
                'title'        => 'Без категории',
                'mailings'    => array(),
                'sub'        => array()
            );
        }

        $aMailGroups['Other']['sub'][] = $item;

        return $aMailGroups;
    }

    private function __cleanMailGroups( $aMailGroups ) {
        // удаляем пустые группы
        foreach( $aMailGroups as $i => $aMailGroup ) {
            if( !isset( $aMailGroup['sub'] ) || empty( $aMailGroup['sub'] ) )
            unset( $aMailGroups[$i] );
        }

        return $aMailGroups;
    }

    public function AddLeadToGroupAction()
    {
        if (!$this->CommonChecks()) {
            return;
        }
        
        $api_can_subscribe = $this->user_rs['api_can_subscribe'];
        
        $this->_loadModel('Db_Leads');
        $this->_loadModel('Db_LeadInfo');
        $this->_loadModel('Db_RassilkiLeads');
        $this->_loadModel('Db_Rassilki');
        
        // с этих IP допустима неограниченая подписка 
        $not_check_ip = [
            '176.9.38.242',   // IP сервера вебинаров
            '178.33.126.200', // IP tigerrr.com
            '5.101.114.152'   // IP Нестеренко
        ];
        
        if (empty($api_can_subscribe) && !$this->isSelfActivate($_POST['service']) && !in_array($_SERVER['REMOTE_ADDR'], $not_check_ip)) {
            // Ограничение в 10 подписок на сутки
            $checkIP = $this->checkIP(10, 60 * 60 * 24);
            if ($checkIP === false) {
                return;
            }
        }

        if (!$this->CheckEmail($_POST['lead_email'])) {
            $this->GenerateResponse(self::ERROR_MISSED_EMAIL, self::ERROR_MISSED_EMAIL_TEXT);
            return;
        }

        $_POST['lead_name'] = empty($_POST['lead_name']) ? "Дорогой друг" : $_POST['lead_name'];
        
        $RASSID = !empty($_POST['rid']) ? $_POST['rid'] : [];

        if (!is_array($RASSID)) {
            $RASSID = array($RASSID);
        }

        sort($RASSID);
        
        if (count($RASSID) > 0) {
            $lead_rs = $this->_model->Db_LeadInfo->GetLeadByEmail( $_POST['lead_email'], $this->user_rs['user_id']);
            
            $rassilki_rs = array();
            $rass_id = array();
            $RASSID2 = array();
            foreach ($RASSID as $k => $v) {
                $v = $this->_model->Db_Rassilki->generateRassName($this->user_rs['user_name'], $v);
                $rass_rs = model_Rassilki::GetByName($v);
                // нет такой группы (стоит уведомить админа чтобы проверил настройки)
                if ($rass_rs === false ) {
                    continue;
                }
                // подписка запрещена (стоит уведомить админа чтобы проверил настройки)
                if ($rass_rs['rass_can_subscribe'] == 2) {
                    continue;
                }
                if(!$this->_model->Db_RassilkiLeads->checkActiveUser($lead_rs['lead_id'], $rass_rs['rass_id'])){
                    // добавляем только рассылки, где нет этого подписчика
                    array_push($RASSID2, $RASSID[$k]);
                    array_push($rassilki_rs, $rass_rs);
                    array_push($rass_id, $rass_rs['rass_id']);
                }
            }
        } else {
            $rass_rs = false;
        }

        if ($rass_rs === false && (!isset($rassilki_rs) || empty($rassilki_rs ))) {
            $this->GenerateResponse(self::NO_ERROR, self::ERROR_GROUP_INVALID_TEXT);
            return;
        }
        
        if (count($rass_id) == 0) {
            // если пользователь уже подписан во всех рассылках
            $this->GenerateResponse(self::NO_ERROR, self::ERROR_ALREADY_REGISTERED_TEXT);
            return;
        }

        // Берем настройку из staff
        $activation = empty($this->user_rs['user_noactivation']);

        // Если активацию не требует staff разрешаем управлять этим процессом из АПИ
        if (!$activation) {
            $activation = !empty($_POST['activation']);
        }
        //Если есть, то ему не высылаем письмо об активации и подписываем сразу
        if (!empty($lead_rs)) {
            if ($activation && intval($lead_rs['lead_status']) == model_Db_LeadInfo::RASSILKI_STATUS_ACTIVE) {
                $activation = false;
            }
            //Если подписчик не активирован, то делаем активацию либо ожидание активации
            if (intval($lead_rs['lead_status']) != model_Db_LeadInfo::RASSILKI_STATUS_ACTIVE
                && intval($lead_rs['lead_status']) != model_Db_LeadInfo::RASSILKI_STATUS_SUBSCRIBE
                && intval($lead_rs['lead_status']) !== model_Db_LeadInfo::RASSILKI_STATUS_UNSUBSCRIBE) {
                $lead_status = (!$activation) ? model_Db_LeadInfo::RASSILKI_STATUS_SUBSCRIBE : model_Db_LeadInfo::RASSILKI_STATUS_WAIT;
            } elseif (intval($lead_rs['lead_status']) === model_Db_LeadInfo::RASSILKI_STATUS_UNSUBSCRIBE) {
                $lead_status = model_Db_LeadInfo::RASSILKI_STATUS_UNSUBSCRIBE;
            }
        } else {
            $lead_status = (!$activation) ? model_Db_LeadInfo::RASSILKI_STATUS_SUBSCRIBE : model_Db_LeadInfo::RASSILKI_STATUS_WAIT;
        }

        // Если активация, то проверяем, есть ли подписчик в базе магазина и Если есть, то его без активации добавим
        if ($activation) {
            if (!empty($lead_rs) && count($lead_rs)>0) {
                $select = $this->_model->Db_RassilkiLeads->GetAllByConditions(true, $this->user_rs['user_id']);
                $this->_model->Db_RassilkiLeads->setFilters(
                    $select,
                    [
                        'active'  => 1,
                        'invalid' => 0,
                        'lid'     => [(int)$lead_rs['lead_id']], // setFilters какого-то хера принимает в lid только массивы
                    ]
                );
                $count = $this->_model->Db_RassilkiLeads->GetCountSubs($select);
                if ($count > 0) {
                    $activation = false;
                }
            }
        }

        // Определяем все маркеры и метки
        App_Leads::CalculateMarkers($_POST['lead_email'], (int)$_POST['aff'], $aff, $content_click);

        $no_err = true;

        $options = isset($_POST['noaff']) ?
            ['noaff' => true] :
            [
                'aff'   => $aff,
                'ad'    => 0,//этот параметр больше не нужен
                'click' => 0,//это клик  для JC_clicks - больше не нужен
                'tag'   => @$_POST['tag']
            ];
        // Статус подписчика либо 2 - неактивирован, либо 1 - активирован
        $options['lead_status'] = $lead_status;
        $options['active'] = $activation ? model_Db_LeadInfo::RASSILKI_STATUS_WAIT : model_Db_LeadInfo::RASSILKI_STATUS_SUBSCRIBE;

        $groups = [];

        // Подписываем на все рассылки
        foreach ($rass_id as $k=>$v) {
            $res = App_Leads::Subscribe(
                $v,
                [
                    'email' => $_POST['lead_email'],
                    'name'  => $_POST['lead_name'],
                    'phone' => isset( $_POST['lead_phone'] ) && !empty( $_POST['lead_phone'] ) ? $_POST['lead_phone'] : '',
                    'city'  => isset( $_POST['lead_city']  ) && !empty( $_POST['lead_city']  ) ? $_POST['lead_city']  : '',
                    'utm'   => (count($_POST['utm']) > 0 && !empty($_POST['utm'])) ? $_POST['utm'] : array()//передача утм-параметров, если юзер их ввел для передачи через АПИ
                ],
                $options,
                $errors,
                $LEAD_ID,
                true,
                $this->user_rs['user_id']
            );
            if ($LEAD_ID > 0) App_Serial::RequeueAllRass($this->user_rs['user_id'], $LEAD_ID);
            
            $groups[] = model_Rassilki::GetByID($v)['rass_title'];
            
            if (!isset($postbackAff)) {
                $postbackAff = $this->_model->Db_RassilkiLeads->GetRowById($v, $LEAD_ID);
            }

            if (empty($utm))
                $utm = App_Clicks_Utm::getPartnerUtmsByRassId($LEAD_ID, $v);

            if (!$res) {
                $this->GenerateResponse(self::ERROR_SUBSCRIBE, self::ERROR_SUBSCRIBE_TEXT.$errors['rassid'].' rass_id='.$v);
                $no_err = false;
            }
        }
        
        //Отправляем postback
        $postback_data = (isset($utm)) ? $utm : [];
        $postback_data['group']    = $groups;
        if (!empty($postbackAff['lead_aff_id'])) {
            App_API::notifyPostback(
                $postbackAff['lead_aff_id'],
                $this->user_rs['user_id'],
                'url_subscribe',
                $postback_data,
                App_API::SEND_RASS
            );
        }
        
        if ($this->isSelfActivate($_POST['service'])) { // если вызов был с систем без активации письмо активации не высылаем
            if ($no_err) {
                if (!empty($postbackAff['lead_aff_id'])) {
                    App_API::notifyPostback(
                        $postbackAff['lead_aff_id'],
                        $this->user_rs['user_id'],
                        'url_activate_subscribe',
                        $postback_data,
                        App_API::SEND_RASS
                    );
                }
                $this->GenerateResponse(self::NO_ERROR, self::NO_ERROR_NO_ACTIVATION);
            }
        } elseif (!$activation) {
            if ($no_err) {
                if (!empty($postbackAff['lead_aff_id'])) {
                    App_API::notifyPostback(
                        $postbackAff['lead_aff_id'],
                        $this->user_rs['user_id'],
                        'url_activate_subscribe',
                        $postback_data,
                        App_API::SEND_RASS
                    );
                }
                $this->GenerateResponse(self::NO_ERROR, self::NO_ERROR_TEXT);
                if (empty($api_can_subscribe)) {
                    $this->blockIP($checkIP);
                }
            }
        } else {
            App_Leads::NeedConfirm($RASSID2, $_POST, $_POST['doneurl2'], $rassilki_rs);
            $this->GenerateResponse(self::NO_ERROR, self::NO_ERROR_ACTIVATION_SEND);
            if (empty($api_can_subscribe)) {
                $this->blockIP($checkIP);
            }
        }
    }
    
    private function isSelfActivate($service_name) {
        if(in_array($service_name, $this->selfActivateService)) return true;
        else return false;
    }
    
    public function ActivateLeadAction() {
        // Активируем подписчика по имейлу (только для ограниченых IP)
        if (!$this->CommonChecks()) return;
        
        if (!$this->isSelfActivate($_POST['service'])) {
            $this->GenerateResponse(self::ERROR_IP_INVALID, self::ERROR_IP_INVALID_TEXT.$_SERVER['REMOTE_ADDR']);
            return;
        }
        
        if (!$this->CheckEmail($_POST['email']))    {
            $this->GenerateResponse(self::ERROR_WRONG_EMAIL, self::ERROR_WRONG_EMAIL_TEXT.$_POST['email']."'");
            return;
        }
        
        $mDbLeadInfo = new model_Db_LeadInfo();
        $mDbRassilkiLeads = new model_Db_RassilkiLeads();
        
        $lead = $mDbLeadInfo->GetLeadByEmail($_POST['email'], $this->user_rs['user_id']);
        if ((int)$lead['lead_status'] === model_Db_LeadInfo::RASSILKI_STATUS_UNSUBSCRIBE) {
            $this->GenerateResponse(self::ERROR_RASS_CANNOT_ACTIVATE, self::ERROR_RASS_CANNOT_ACTIVATE_TEXT);
            return;
        }
        if(!empty($lead)) {
            $leadId = $lead['lead_id'];
            //Отправляем постбэки
            $rassids = $mDbRassilkiLeads->GetNonActiveRassId($leadId, $this->user_rs['user_id']);
            foreach ($rassids as $k=>$v) {
                if (empty($utm))
                    $utm = App_Clicks_Utm::getPartnerUtmsByRassId($leadId, $v['rass_id']);
                $groups[] = model_Rassilki::GetByID($v['rass_id'])['rass_title'];
                $lead_aff = $v['lead_aff_id'];
            }
            $postback_data = (isset($utm)) ? $utm : [];
            $postback_data['group'] = $groups;
            App_API::notifyPostback($lead_aff, $this->user_rs['user_id'], 'url_activate_subscribe', $postback_data, App_API::SEND_RASS);
            
            // активируем во всех группах
            $mDbLeadInfo->update(
                ['lead_status' => model_Db_LeadInfo::RASSILKI_STATUS_SUBSCRIBE]
                , "lead_id = {$leadId} AND owner_id = {$this->user_rs['user_id']}"
            );
            $mDbRassilkiLeads->SetActive($leadId, $this->user_rs['user_id']);
            if ($leadId > 0) App_Serial::RequeueAllRass($this->user_rs['user_id'], $leadId);
            $this->GenerateResponse(self::NO_ERROR, self::NO_ERROR_TEXT);
        }
        else {
            $this->GenerateResponse(self::ERROR_SUBSCRIBER_NOTFOUND, self::ERROR_SUBSCRIBER_NOTFOUND_TEXT);
                return;
        }
    }
    public function CreateOrderAction() {
        if (!$this->CommonChecks()) return;
        if(!$this->user_rs['api_can_orders_without_limit'])
        {
            $this->GenerateResponse(self::ERROR_ORDER_API_DISABLED, self::ERROR_ORDER_API_DISABLED_TEXT);
            return;
        }

        if(!$this->CheckEmail($_POST['bill_email']))    {
            $this->GenerateResponse(self::ERROR_WRONG_EMAIL, self::ERROR_WRONG_EMAIL_TEXT.$_POST['bill_email']."'");
            return;
        }
        
        if (empty($_POST['goods']) || !is_array($_POST['goods']) || count($_POST['goods']) <= 0) {
            $this->GenerateResponse(self::ERROR_ORDER_NO_PRODUCTS, self::ERROR_ORDER_NO_PRODUCTS_TEXT);
            return;
        }
        
        $goods = array();
        $goods_sum = array();

        $mDbGoods = new model_Db_Goods;
        foreach($_POST['goods'] as $good){
            $good_name = $this->user_rs['user_name'].'_'.$good['good_name'];
            $good_rs = $mDbGoods->GetByName($good_name, $this->user_rs['user_id']);
            if (empty($good_rs)){
                $this->GenerateResponse(self::ERROR_ORDER_NO_GOOD, self::ERROR_ORDER_NO_GOOD_TEXT.$good['good_name']);
                return;
            }
            if ($good_rs['good_type'] == ShopGood::GOOD_TYPE_PHISICAL && (
                empty($_POST['bill_first_name']) ||
                empty($_POST['bill_surname']) ||
                empty($_POST['bill_country']) ||
                empty($_POST['bill_city']) ||
                empty($_POST['bill_address'])
            )){ // для физического продукта должны быть заданы данные для пересылки
                $this->GenerateResponse(self::ERROR_ORDER_NO_DELIVERY, self::ERROR_ORDER_NO_DELIVERY_TEXT);
                return;
            }
            array_push($goods, $good_rs['good_id']);
            if(!empty($good['good_sum'])) {
                $goods_sum[$good_rs['good_id']] = $good['good_sum'];
            }

        }
        unset($_POST['goods']);

        if(count($_POST) == 0) {
            $this->GenerateResponse(self::ERROR_MISSING_PARAM, self::ERROR_MISSING_PARAM_TEXT);
            return;
        }
        $bill_data = $_POST;
        $bill_data['owner_id'] = $this->user_rs['user_id'];
        $bill_data['bill_ip'] = $_SERVER['REMOTE_ADDR'];

        // Уберем лишние http:// , https:// , http:// и /
        $bill_data['bill_domain'] = str_replace("http://", "", $bill_data['bill_domain']);
        $bill_data['bill_domain'] = str_replace("https://", "", $bill_data['bill_domain']);
        $bill_data['bill_paff'] = (array) $bill_data['bill_paff'];
        if (substr($bill_data['bill_domain'], -1) == '/') {
            $bill_data['bill_domain'] = substr($bill_data['bill_domain'], strlen($bill_data['bill_domain'])-1);
        }
        /* Больше не используется, устаревший метод
        ShopBill::CalculateMarkers(
            $this->user_rs['user_id']
            , $_POST['bill_email']
            , $_POST['aff']
            , $bill_aff
            , $bill_paff
            , $bill_data['bill_ad_id']
            , $bill_data['bill_click_id']
        );*/

        $mDbUsers = new model_Db_Users;

        // Ищем ID партнёра, если передан его ник 
        if(!empty($bill_data['bill_aff_name']) && empty($bill_data['bill_aff']))
            $bill_aff = $mDbUsers->GetIdByName( $bill_data['bill_aff_name']);

        if(!empty($bill_data['bill_paff_name']) && empty($bill_data['bill_paff']))
            $bill_paff = (array) $mDbUsers->GetIdByName( $bill_data['bill_paff_name']);
        
        // меняем партнёров, только если они не заданы жёстко в запросе
        if(empty($bill_data['bill_aff']))
            $bill_data['bill_aff']= $bill_aff;
        if(empty($bill_data['bill_paff']))
            $bill_data['bill_paff'] = $bill_paff;
         
        // Если ID рекламной метки жёстко передан в запросе, устанавливаем его
        /* Больше не используется, это старый формат меток
        if(!empty($_POST['bill_ad_id'])) {
            // Проверяем есть ли у пользователя такая метка
            $mDbAds = new model_Db_Ads;
            $ad_id = $mDbAds->GetByID( $_POST['bill_ad_id'], $this->user_rs['user_id']);
            if(!empty($ad_id))
                $bill_data['bill_ad_id'] = $_POST['bill_ad_id'];
        }*/

        $bill_id = ShopBill::CreateFromGoods($goods, $bill_data, $goods_sum, $is_copy, true);

        // Для системного вызова не будем кидать ошибку что счет уже есть, отдадим сразу далее bill_id и bill_sum (используется в подписке на тариф)
        if($is_copy && !isset($_POST['system_call'])) {
            $this->GenerateResponseResult(self::ERROR_ORDER_ALREADY, self::ERROR_ORDER_ALREADY_TEXT, array("bill_id" => $bill_id));
            return;
        }
        
        if(empty($bill_id)) {
            $this->GenerateResponse(self::ERROR_ORDER_CREATED, self::ERROR_ORDER_CREATED_TEXT);
            return;
        }

        $res["bill_id"] = $bill_id;
        if(isset($_POST['system_call'])) {
            $res["bill_sum"] = ShopBill::TotalSum($bill_id); // Для системного вызова еще и сумму заказа отдадим
        }
        
        $this->GenerateResponseResult(self::NO_ERROR, self::NO_ERROR_ORDER_CREATED, $res);
    }

    public function UpdateOrderStatusAction() {
        if (!$this->CommonChecks()) return;

        $_POST['bill_id'] = (int) @$_POST['bill_id'];

        // Если не передан номер и статус заказа
        if (
            $_POST['bill_id'] <= 0
            || empty($_POST['status'])
            || ( $_POST['status'] == 'moneyback' && empty($_POST['ext_ref_no']))
        ) {
            // Ответ сервера - неверные данные
            $this->GenerateResponse(self::ERROR_WRONG_DATA, self::ERROR_WRONG_DATA_TEXT);
            return;
        }

        // Проверяем существование заказа
        $mDbBills = new model_Db_Bills();
        $res = $mDbBills->GetByID( $_POST['bill_id'], $this->user_rs['user_id']);
        if( empty($res))
        {
            // Ответ сервера - несуществующий заказ
            $this->GenerateResponse(self::ERROR_NONEXISTENT, self::ERROR_NONEXISTENT_TEXT);
            return;
        }
        
        $sum = isset($_POST['sum']) && !empty($_POST['sum']) ? $_POST['sum'] : true;
        
        $goods = ShopBill::BillGoods($_POST['bill_id']);
        
        switch ($_POST['status'])
        {
            case 'sent':
                $_POST['date'] = (int) @$_POST['date'];
                $_POST['rpo'] = trim($_POST['rpo']);

                // Проверяем данные на пустоту
                if ($_POST['date'] <= 0 || empty($_POST['rpo']))
                {
                    // Ответ сервера - неверные данные
                    $this->GenerateResponse(self::ERROR_WRONG_DATA, self::ERROR_WRONG_DATA_TEXT);
                    return;
                }

                // Обновляем данные заказа: дату отправки, номер РПО, статус
                $mDbBills->updateBill($_POST['bill_id'], array(
                    'bill_delivered' => $_POST['date'],
                    'bill_delivery_comment' => "Номер почтового отправления: " . htmlspecialchars( $_POST['rpo']). "",
                    'bill_delivery_status' => 'sent',
                ));

                // Ответ сервера - успешное выполнение
                $this->GenerateResponse(self::NO_ERROR, self::NO_ERROR_TEXT);
                break;
            case 'paid':
                $_POST['date'] = (int) @$_POST['date'];
                // Проверяем данные на пустоту
                if ($_POST['date'] <= 0)
                {
                    // Ответ сервера - неверные данные
                    $this->GenerateResponse(self::ERROR_WRONG_DATA, self::ERROR_WRONG_DATA_TEXT);
                    return;
                }

                $pay_sys = "RussianPostService";

                // Если вызов системный
                if( isset( $_POST[ 'system_call']))
                    $pay_sys = "JustClick";

                if( isset( $_POST[ 'payway' ] ) && !empty( $_POST[ 'payway' ] ) ) {
                    $pay_sys = "JustClick";
                    $realway = $_POST['payway'];
                    $pay_variant = $_POST['pay_variant'];
                }
                else
                    $realway = null;
                
                $prepay_code = isset($_POST['prepay_code']) && !empty($_POST['prepay_code']) ? $_POST['prepay_code'] : null;

                $token = $_POST['token'];
                // Для рекуррентного счёта создадим неактивный крон
                if (!empty($token)) {
                    $mDbRecurrentCron = new model_Db_RecurrentCron();
                    $rc_exist = $mDbRecurrentCron->getByCode($token);
                    if (empty($rc_exist)) {
                        // если $rc_exist пустая - оплата первичная
                        $good = ShopGood::GetByID($goods[0]['good_id']);
                        $firstPayInterval = (empty($goods[0]['first_recurrent']) ? $goods[0]['period_recurrent'] : $goods[0]['first_recurrent']);
                        $ins = array (
                            'active' => 0, // для начала вставим неактивный крон
                            'date_payment' => time(),
                            'next_payment' => time() + $firstPayInterval * 86400,
                            'opkey' => $token,
                            'user_id' => $this->user_rs['user_id'],
                            'type' => in_array($goods[0]['good_name'], ShopUser::$GOODS) ? 'j' : 'g',
                            'period' => $goods[0]['period_recurrent'],
                            'first_sum' => $goods[0]['good_sum'],  // сумма из счёта - первое списание
                            'sum' => $good['good_sum'], // цена товара - остальные списания
                            'rec_count' => 1,
                            'rec_count_total' => $goods[0]['good_recurrent_max_count'] == null ? null : $goods[0]['good_recurrent_max_count'] + 1,
                            'payout' => 'payu',
                            'bill_id' => $_POST['bill_id'],
                            'good_id' => $goods[0]['good_id'],
                            'errors' => ''
                        );
                        $mDbRecurrentCron->AddBill($ins);
                    }
                }
                
                //Параметры предоплаты
                $prepayment_options = array();
                //Разрешить предоплату для всех продуктов (даже для тех у которых не стоит галочка)
                if (isset($_POST['prepayment_enabled']))
                    $prepayment_options['enabled'] = (bool)$_POST['prepayment_enabled'];
                //Зачислить предоплату на баланс
                if (isset($_POST['prepayment_to_balance']))
                    $prepayment_options['to_balance'] = (bool)$_POST['prepayment_to_balance'];
                //Увеличить сумму заказа, если оплатили больше положенного
                if (isset($_POST['prepayment_inc_sum']))
                    $prepayment_options['inc_sum'] = (bool)$_POST['prepayment_inc_sum'];
                
                // Оплачивается заказ
                $res = ShopBill::Pay(
                    $_POST['bill_id']
                    , $sum
                    , $pay_sys
                    , $_POST[ 'date']
                    , 1
                    , false
                    , $realway
                    , $prepay_code
                    , $token // Для случая рекурентных платежей
                    , $pay_variant
                    , $prepayment_options
                );

                if ($res === false)
                {
                    // Ответ сервера - заказ не оплачен
                    $this->GenerateResponse(self::ERROR_NOT_PAID, self::ERROR_NOT_PAID_TEXT);
                    return;
                }

                // Обновление статуса заказа
                $mDbBills->updateBill($_POST['bill_id'], array(
                    'bill_paid' => $_POST['date'],
                ));

                // Для рекуррентного счёта активируем крон и увеличим счетчик платежей
                if (!empty($token)) {
                    $mDbRecurrentCron = new model_Db_RecurrentCron();
                    $rc = $mDbRecurrentCron->getByCode($token);
                    $mDbRecurrentCron->makeActive($rc['id']);

                    $mDbRL = new model_Db_RecurrentLog();
                    $log = array(
                        'created' => time(),
                        'type' => 'PayU',
                        'status' => 1,
                        'message' => 'Успешная оплата рекуррентного счёт №'.$_POST['bill_id'],
                        'user_id' => $this->user_rs['user_id'],
                        'key' => $token
                    );
                    $mDbRL->AddData($log);
                }
                // Ответ сервера - успешное выполнение
                $this->GenerateResponse( self::NO_ERROR, self::NO_ERROR_TEXT);
                break;
            case 'return':
                ShopBill::ChangeDeliveryStatus($_POST['bill_id'], 'return');
                // Ответ сервера - успешное выполнение
                $this->GenerateResponse(self::NO_ERROR, self::NO_ERROR_TEXT);
                break;
            case 'cancel':
                ShopBill::ChangePayStatus($_POST['bill_id'], 'cancel');
                // Ответ сервера - успешное выполнение
                $this->GenerateResponse(self::NO_ERROR, self::NO_ERROR_TEXT);
                break;
            case 'moneyback':
                $resp = ShopBill::ChangePayStatus( $_POST[ 'bill_id'], 'moneyback');
                // Ответ сервера - успешное выполнение
                if( $resp === true){
                    // Если статус обновился, вызов системный и требуется возврат реальных денег
                    if( $_POST[ 'system_call'] == 1 && $_POST[ 'refund'] == 1){

                        $mDbJustclickBills = new model_Db_JustclickBills;
                        $jc_bill = $mDbJustclickBills->getByExtRefNo( $this->user_rs[ 'user_id'], $_POST['ext_ref_no']);

                        if( !empty( $jc_bill)){

                            $response = App_Payment_JustClick::GetPayment( 
                                    App_Payment_JustClick::$PAYWAYS[ $jc_bill[ 'payway']]
                                )
                                ->moneyback( $jc_bill)
                            ;

                            // Ошибка
                            if ( $response[ 'code'] != 0) {
                                trigger_error( 'Moneyback error: '.$response['code'].' : '. $response['message'], E_USER_ERROR);
                                $this->GenerateResponse( self::ERROR_REFUND_FAIL, self::ERROR_REFUND_FAIL_TEXT . ': #' . $response['code'] . ' - ' . $response['message']);
                            }else{
                                $mDbPayouts = new model_Db_Payouts;
                                $mDbPayouts->Add(
                                    $jc_bill[ 'owner_id']
                                    , $jc_bill[ 'account_id']
                                    , -$jc_bill[ 'bill_sum'] * ( ( 100 - (int)$this->_config->settings->payouts_fee) / 100)
                                    , 'Возврат средств по счету № '.$jc_bill[ 'bill_id']
                                    , $jc_bill[ 'bill_id']
                                    , $jc_bill[ 'extra']
                                    , null
                                    , model_Db_Payouts::MONEYBACK
                                    , time()
                                );
                                $mDbPayouts->Add(
                                    $jc_bill[ 'owner_id']
                                    , $jc_bill[ 'account_id']
                                    , -$jc_bill[ 'bill_sum'] * ( (int)$this->_config->settings->payouts_fee / 100)
                                    , 'Комиссия за возврат средств по счету № '.$jc_bill[ 'bill_id']
                                    , $jc_bill[ 'bill_id']
                                    , $jc_bill[ 'extra']
                                    , null
                                    , model_Db_Payouts::MONEYBACK_FEE
                                    , time()
                                );
                                $moneyback_time = time();
                                $mDbJustclickBills->update(
                                    array( 'bill_moneyback' => $moneyback_time)
                                    , array(
                                        'ext_ref_no = ?' => $jc_bill[ 'ext_ref_no']
                                    )
                                );
                                $mDbJustclickBills->insert(
                                    array(
                                        'username'        => $jc_bill[ 'username']
                                        , 'extra'        => $jc_bill[ 'extra']
                                        , 'payway'        => $jc_bill[ 'payway']
                                        , 'owner_id'    => $jc_bill[ 'owner_id']
                                        , 'bill_id'        => $jc_bill[ 'bill_id']
                                        , 'bill_create'    => $moneyback_time
                                        , 'bill_paid'    => $moneyback_time
                                        , 'bill_sum'    => -$jc_bill[ 'bill_sum']
                                        , 'bill_moneyback'    => $moneyback_time
                                        , 'pay_data'    => $jc_bill[ 'pay_data']
                                        , 'pay_type'    => $jc_bill[ 'pay_type']
                                        , 'ext_ref_no'    => $jc_bill[ 'ext_ref_no']
                                        , 'pay_variant'    => $jc_bill[ 'pay_variant']
                                        , 'account_id'    => $jc_bill[ 'account_id']
                                    )
                                );

                                $this->GenerateResponse( self::NO_ERROR, self::NO_ERROR_STATUS_CHANGED);
                            }
                        }
                        
                        $mDbJkBills = new model_Db_JustkassaBills();
                        $jkBill = $mDbJkBills->getByBillAndOwner($_POST[ 'bill_id'], $this->user_rs[ 'user_id']);
                        if (!empty($jkBill)) {
                            $response = App_Payment_JustKassa::getPayment(App_Payment_JustKassa::$PAYWAYS[$jkBill['payway']])->moneyback($jkBill);
                            if ($response['code'] != 0) {
                                trigger_error('Moneyback error: '.$response['code'].' : '. $response['message'], E_USER_ERROR);
                                $this->GenerateResponse(self::ERROR_REFUND_FAIL, self::ERROR_REFUND_FAIL_TEXT . ': #' . $response['code'] . ' - ' . $response['message']);
                            }
                            else{
                                $moneyback_time = time();
                                $mDbJkBills->update([
                                    'bill_moneyback' => $moneyback_time
                                ], [
                                    'owner_id = ?' => $jc_bill[ 'owner_id'],
                                    'bill_id = ?' => $jc_bill[ 'bill_id']
                                ]);
                                $mDbJkBills->insert([
                                    'username'        => $jc_bill[ 'username'],
                                    'extra'        => $jc_bill[ 'extra'],
                                    'payway'        => $jc_bill[ 'payway'],
                                    'owner_id'    => $jc_bill[ 'owner_id'],
                                    'bill_id'        => $jc_bill[ 'bill_id'],
                                    'bill_create'    => $moneyback_time,
                                    'bill_paid'    => $moneyback_time,
                                    'bill_sum'    => -$jc_bill[ 'bill_sum'],
                                    'bill_moneyback'    => $moneyback_time,
                                    'pay_data'    => $jc_bill[ 'pay_data'],
                                    'pay_type'    => $jc_bill[ 'pay_type'],
                                    'ext_ref_no'    => $jc_bill[ 'ext_ref_no'],
                                    'pay_variant'    => $jc_bill[ 'pay_variant'],
                                    'account_id'    => $jc_bill[ 'config_id']
                                ]);

                                $this->GenerateResponse( self::NO_ERROR, self::NO_ERROR_STATUS_CHANGED);
                            }
                        }
                    }else{
                        $this->GenerateResponse( self::NO_ERROR, self::NO_ERROR_STATUS_CHANGED);
                    }
                }else{
                     $this->GenerateResponse( self::ERROR_ORDER_STATUS_NOT_CHANGED, self::ERROR_ORDER_STATUS_NOT_CHANGED_TEXT);
                }
                break;
            case 'fromjc':
                // Обновление статуса заказа
                $mDbBills->updateBill($_POST['bill_id'], array(
                    'bill_payway' => 'JustClick',
                    'bill_paid' => $_POST['date'],
                    'bill_deleted' => 0,
                ));
                
                $good_names = array();
                if(!empty($goods)) {
                    foreach ($goods as &$good_rs) {
                        $good_names[] = $good_rs[ 'good_title'];
                    }
                }
                
                $res['bill_payway'] = 'Admin';
                
                if($sum === TRUE) {
                    $sum = ShopBill::TotalSum($_POST['bill_id']);
                }
                
                ShopBill::AddToJCBills($res, $sum, $_POST['date'], $good_names, 'receipt');
                
                $this->GenerateResponse(self::NO_ERROR, self::NO_ERROR_TEXT);
                break;
            default:
                // Ответ сервера - неверный статус заказа
                $this->GenerateResponse(self::ERROR_WRONG_STATUS, self::ERROR_WRONG_STATUS_TEXT);
                return;
                break;
        }
    }
    
    public function CancelRecurrentTariffAction() {
        
        if (!$this->CommonChecks()) return;
        
        // Вызов может быть только системным
        if(empty($_POST['bill_id']) || !isset($_POST['system_call']))
        {
            self::GenerateResponse(self::ERROR_WRONG_DATA, self::ERROR_WRONG_DATA_TEXT);
            return;
        }
        
        $bill_id = $_POST['bill_id'];
        
        $mDbRC = new model_Db_RecurrentCron();
        $mDbRL = new model_Db_RecurrentLog();
        $mDbRB = new model_Db_RecurrentBills();
        
        $rid = $mDbRB->GetRidByBid($bill_id);
        $rc = $mDbRC->getByID($rid);
        
        $message = 'Ручная отписка от услуги пользователем (нажал на кнопку в /profile/unlim/)';
        $mDbRC->makeUnActive($rc['id'], $message);
        $log = array(
            'created' => time(),
            'type' => $rc['payout'].' (пользователь)',
            'status' => 0,
            'message' => $message,
            'user_id' => $rc['user_id'],
            'key' => $rc['opkey']
        );
        $mDbRL->AddData($log);
        // если подписаны на рассылки, привязанные к рекуретному товару - отписываем через время
        CronPhp::addTask(App_Recurrent::MakeLink($rc['id'], $rc['opkey'], (int)$rc['user_id'], 'unsub', true), null, ($rc['next_payment'] - time()) / 60);

        if ($rc['payout'] == 'payu') {
            // Конфиг PayU
            $cfg = $this->_config->pay->payu->toArray();
            require_once(CMS_LIB_PATH.'App/Payment/Vendors/PayU.php');
            $PayU = new PayU('', $cfg['merchantName'], $cfg['secretKey']);
            $result = $PayU->deleteTokenPayment($rc['opkey'], $message);
        }
        else {
            // yandex recurrent
            $result['code'] = 0;
        }
        
        if ($result['code'] == 0 || $result['code'] == 2200) {
            self::GenerateResponse(self::NO_ERROR, self::NO_ERROR_TEXT);
            return;
        }
        
        self::GenerateResponse(self::ERROR_CANCEL_RECURRENT, self::ERROR_CANCEL_RECURRENT_TEXT . ': #' . $result['code'] . ' - ' . $result['message']);
    }

    public function DeleteOrderAction() {
        if (!$this->CommonChecks()) return;

        if(empty($_POST['bill_id']))
        {
            self::GenerateResponse(self::ERROR_ORDER_EMPTY, self::ERROR_ORDER_EMPTY_TEXT);
            return;
        }
        $mDbBills = new model_Db_Bills;

        $res = $mDbBills->GetByID( $_POST['bill_id'], $this->user_rs['user_id']);
        if( !empty($res['bill_id']))
        {
            $mDbBills->deleteBill($_POST['bill_id']);
            $this->GenerateResponse(self::NO_ERROR, self::NO_ERROR_TEXT);
            return;
        }
        else
        {
            $this->GenerateResponse(self::ERROR_NONEXISTENT, self::ERROR_NONEXISTENT_TEXT);
            return;
        }
    }

    public function AddGoodAction() {
        if (!$this->CommonChecks()) return;

        //если название и идентификатор продукта не указаны
        if(!empty($_POST['good_name']) && !empty($_POST['good_title']) && !empty($_POST['good_type']) && !empty($_POST['good_sum']))
        {
            $_POST['owner_id'] = $this->user_rs['user_id'];
            $_POST['good_type'] = $_POST['good_type'] == 'real' ? 1 : 0;
            
            $_POST['good_name'] = $this->user_rs['user_name'].'_'.$_POST['good_name'];
            $mDbGoods = new model_Db_Goods;
            $res = $mDbGoods->GetByName($_POST['good_name'], $this->user_rs['user_id']);
            if( empty($res))
            {
                $mDbRassilki = new model_Db_Rassilki;
                // ищем или создаём рассылки для покупателей
                $rass_id = array();
                $rass_rs = explode(',', $_POST['good_client_rassilki']);
                foreach($rass_rs as $rass){
                    $rass = trim($rass);
                    $rass_name = $mDbRassilki->generateRassName($this->user_rs['user_name'], $rass);

                    $r_test = $mDbRassilki->GetByName($this->user_rs['user_id'], $rass_name);
                    if ($r_test !== false)
                    {
                        array_push($rass_id, $r_test['rass_id']);
                    }
                    else
                    {
                        $r_id = $mDbRassilki->addClientRass( array(
                            'rass_name' => $rass_name,
                            'owner_id' => $this->user_rs['user_id'],
                            'rass_can_subscribe' => 0,
                            'rass_title' => "Клиенты продукта: \"{$_POST['good_title']}\"",
                            'rass_activation_text' => 'Если Вы действительно подписывались на эти рассылки, то, пожалуйста, подтвердите подписку, кликнув на ссылку:',
                        ));
                        array_push($rass_id, $r_id);
                    }
                }
                $_POST['good_client_rassilki'] = serialize($rass_id);

                $mDbGoods = new model_Db_Goods();
                $ress = $mDbGoods->InsertGood($_POST);
                if($ress == true)
                {
                    $this->GenerateResponse(self::NO_ERROR, self::NO_ERROR_TEXT);
                    return;
                }
                else
                {
                    $this->GenerateResponse(self::ERROR_NOTADD_PRODUCT, self::ERROR_NOTADD_PRODUCT_TEXT);
                    return;
                }
            }
            else
            {
                $this->GenerateResponse(self::ERROR_ALREADY_PRODUCT, self::ERROR_ALREADY_PRODUCT_TEXT);
                return;
            }
        }
        else
        {
            $this->GenerateResponse(self::ERROR_MISSING_PARAM, self::ERROR_MISSING_PARAM_TEXT);
            return;
        }
    }

    public function DeleteGoodAction() {
        if (!$this->CommonChecks()) return;

        //если идентификатор продукта не указан
        if(!empty($_POST['good_name']))
        {
            $_POST['good_name'] = $this->user_rs['user_name'].'_'.$_POST['good_name'];
            $mDbGoods = new model_Db_Goods;
            $ress = $mDbGoods->deleteGoodByName( $_POST['good_name'], $this->user_rs['user_id']);
            //если операция удаления прошла успешно
            if($ress === true) 
            {
                $this->GenerateResponse(self::NO_ERROR, self::NO_ERROR_TEXT);
                return;
            }
            else
            {
                $this->GenerateResponse(self::ERROR_NOTREMOVE_PRODUCT, self::ERROR_NOTREMOVE_PRODUCT_TEXT);
                return;
            }
        }
        else
        {
            $this->GenerateResponse(self::ERROR_MISSING_PARAM, self::ERROR_MISSING_PARAM_TEXT);
            return;
        }
    }

    public function UpdateGoodAction() {
        if (!$this->CommonChecks()) return;

        //если идентификатор продукта не указан
        if(!empty($_POST['good_name']))    
        {
            $_POST['good_name'] = $this->user_rs['user_name'].'_'.$_POST['good_name'];
            $mDbGoods = new model_Db_Goods;
            $res = ShopGood::GetByName($_POST['good_name']);
            //если данный продукт существует
            if(!empty($res))
            {
                unset($_POST['good_name']);
                $_POST['owner_id'] = $this->user_rs['user_id'];
                $_POST['good_status'] = $res['good_status'];
                $ress = $mDbGoods->UpdateGood( $_POST, $res['good_id']);
                //если операция обновления прошла успешно
                if($ress == true) 
                {
                    $this->GenerateResponse(self::NO_ERROR, self::NO_ERROR_TEXT);
                    return;
                }
                else
                {
                    $this->GenerateResponse(self::ERROR_NOTREMOVE_PRODUCT, self::ERROR_NOTREMOVE_PRODUCT_TEXT);
                    return;
                }
            }
            else
            {
                $this->GenerateResponse(self::ERROR_PRODUCT_NOTFOUND, self::ERROR_PRODUCT_NOTFOUND_TEXT);
                return;
            }
        }
        else
        {
            $this->GenerateResponse(self::ERROR_MISSING_PARAM, self::ERROR_MISSING_PARAM_TEXT);
            return;
        }
    }

    public function GetBillsAction() {
        if (!$this->CommonChecks()) return;

        // Получение списка купленных продуктов по мейлу клиента
        if ($this->CheckEmail($_POST['email'])) {

            $mDbBills = new model_Db_Bills;
            $res_bills = $mDbBills->getBillGoodsByConditions( $this->user_rs['user_id'], $_POST['email'], $_POST['pay_status']); 
            if( !empty( $res_bills))
            {
                $this->GenerateResponseResult(self::NO_ERROR, self::NO_ERROR_TEXT, $res_bills);
                return;
            }
            else
            {
                $this->GenerateResponse(self::ERROR_ORDER_NOTFOUND, self::ERROR_ORDER_NOTFOUND_TEXT);
                return;
            }
        }
        else
        {
            $this->GenerateResponse(self::ERROR_WRONG_DATA, self::ERROR_WRONG_DATA_TEXT);
            return;
        }
    }

    public function GetLeadGroupsAction() {
        if (!$this->CommonChecks()) return;

        $mDbRassilki = new model_Db_Rassilki();

        //Получение групп подписчиков по мейлу клиента
        if ($this->CheckEmail($_POST['email'])) {

            $mDbRassilkiLeads = new model_Db_RassilkiLeads;
            $res_rass = $mDbRassilkiLeads->getGroupsByConditions( $_POST[ 'email'], $this->user_rs[ 'user_id']);

            if ( !empty( $res_rass))
            {
                foreach ($res_rass as $value)
                {
                    $res[] = array(
                        'rass_name' => $mDbRassilki->extractRassName($value['rass_name'], $this->user_rs['user_name']),
                        'rass_title' => $value[ 'rass_title'],
                    );
                }
                $this->GenerateResponseResult(self::NO_ERROR, self::NO_ERROR_TEXT, $res);
                return;
            }
            else
            {
                $this->GenerateResponse(self::ERROR_GROUP_NOTFOUND, self::ERROR_GROUP_NOTFOUND_TEXT);
                return;
            }
        }
        else
        {
            $this->GenerateResponse(self::ERROR_WRONG_DATA, self::ERROR_WRONG_DATA_TEXT);
            return;
        }
    }

    public function GetAllGroupsAction() {
        if (!$this->CommonChecks()) return;

        //Получение всех групп подписчиков из аккаунта (кроме автогрупп)
        $mDbRassilki = new model_Db_Rassilki;
        $res_all_rass_id = $mDbRassilki->GetAllRassByUserId(
                            $this->user_rs[ 'user_id']
                            , false
                            , array( model_Db_Rassilki::TYPE_STANDART, model_Db_Rassilki::TYPE_CLIENTS)
                        );
        if (!empty($res_all_rass_id))
        {
            foreach($res_all_rass_id as $key => $value)
            {
                $res[] = array(
                    'rass_id' => $value[ 'rass_id'], 
                    'rass_name' => $mDbRassilki->extractRassName($value['rass_name'], $this->user_rs['user_name']),
                    'rass_title' => $value[ 'rass_title'],
                );
            }
            $this->GenerateResponseResult(self::NO_ERROR, self::NO_ERROR_TEXT, $res);
            return;
        }
        else
        {
            $this->GenerateResponse(self::ERROR_GROUP_NOTFOUND, self::ERROR_GROUP_NOTFOUND_TEXT);
            return;
        }
    }

    public function UpdateSubscriberDataAction() {
        if (!$this->CommonChecks()) return;
        
        if(!$this->CheckEmail($_POST['lead_email']))
        {
            $this->GenerateResponse(self::ERROR_MISSED_EMAIL, self::ERROR_MISSED_EMAIL_TEXT);
            return;
        }

        // Проверка принадлежности лида к овнеру. (поиск по рассылкам овнера)
        $mDbRL = new model_Db_RassilkiLeads();
        $lead_cnt = $mDbRL->GetCountExist( $_POST['lead_email'], $this->user_rs[ 'user_id']);
        if( empty($lead_cnt)){
            $this->GenerateResponse(self::ERROR_SUBSCRIBER_NOTFOUND, self::ERROR_SUBSCRIBER_NOTFOUND_TEXT);
            return;
        }

        //Изменение данных подписчика
        $modEmailLead = new model_Leads();

        $res_email_lead = $modEmailLead->checkExistEmail($_POST['lead_email']);
        
        if (!empty($res_email_lead['lead_id']))
        {
            $res = $modEmailLead->Update($res_email_lead['lead_id'], $_POST);
            $this->GenerateResponse(self::NO_ERROR, self::NO_ERROR_TEXT);
            return;
        }
        else
        {
            $this->GenerateResponse(self::ERROR_SUBSCRIBER_NOTFOUND, self::ERROR_SUBSCRIBER_NOTFOUND_TEXT);
            return;
        }
    }

    public function GetAllGoodsAction() {
        if (!$this->CommonChecks()) return;

        //Получение всех товаров из аккаунта
        $modAllGoods = new model_Goods();
        $res_all_goods = $modAllGoods->GetAllByOwnerId ($this->user_rs['user_id'], array('good_name','good_title', 'good_type', 'good_sum'));
        if(is_array($res_all_goods) || count($res_all_goods) > 0) {
            for($i=0; $i < count($res_all_goods); $i++) {
                $res_all_goods[$i]['good_name'] = substr($res_all_goods[$i]['good_name'], strpos($res_all_goods[$i]['good_name'], '_')+1);
            }
            $this->GenerateResponseResult(self::NO_ERROR, self::NO_ERROR_TEXT, $res_all_goods);
            return;
        }
        else
        {
            $this->GenerateResponse(self::ERROR_NO_PRODUCTS, self::ERROR_NO_PRODUCTS_TEXT);
            return;
        }
    }

    public function GetBillGoodsAction() {
        if (!$this->CommonChecks()) return;
        
        $mDbGoods = new model_Db_Goods;
        
        $fields = array('good_id', 'good_success_link');
        
        // Эти поля нужны для интеграции способа оплаты JC с PayU
        if(isset($_POST['more_info'])) {
            $fields[] = 'good_sum';
            $fields[] = 'good_title';
        }
        
        $res = $mDbGoods->GetAllByUserAndBill(
            $this->user_rs[ 'user_id']
            , $_POST[ 'bill_id']
            , $fields
        );
        
        if( !empty( $res)){
            $this->GenerateResponseResult(self::NO_ERROR, self::NO_ERROR_TEXT, $res);
            return;
        }else{
            $this->GenerateResponse(self::ERROR_NO_PRODUCTS, self::ERROR_NO_PRODUCTS_TEXT);
            return;
        }
    }


    public function DeleteSubscribeAction() {
        if (!$this->CommonChecks()) return;

        //Удаление подписчика из группы
        $mDbLeadInfo = new model_Db_LeadInfo();
        $mDbRassilki = new model_Db_Rassilki();

        $rass = $_POST['rass_name'];
        if (empty($rass)) {
            $rass = array();
        }
        if (!is_array($rass)) {
            $rass = array($rass);
        }

        foreach($rass as $ras) {
            //Получаем ID группы
            $res_rass_id = $mDbRassilki->GetByName(
                $this->user_rs['user_id'],
                $mDbRassilki->generateRassName($this->user_rs['user_name'], $ras)
            );
            if(!empty($res_rass_id)) {
                //Получаем ID подписчика
                $res_lead_id = $mDbLeadInfo->GetEmailIds($_POST['lead_email'], $this->user_rs['user_id']);
                if(!empty($res_lead_id)) {
                    //Удаляем (делаем неактивным) подписчика
                    $res_rass_group = App_Leads::Unsubscribe($res_rass_id['rass_id'], $res_lead_id[0]);
                }
                else {
                    $this->GenerateResponse(self::ERROR_SUBSCRIBE_ADDRESS_NOT_FOUND, self::ERROR_SUBSCRIBE_ADDRESS_NOT_FOUND_TEXT);
                    return;
                }
            }
            else {
                $this->GenerateResponse(self::ERROR_GROUP_NOT_FOUND, self::ERROR_GROUP_NOT_FOUND_TEXT);
                return;
            }
        }
        $this->GenerateResponse(self::NO_ERROR, self::NO_ERROR_TEXT);
        return;
    }

    //Получение информации заказа по его ID
    public function GetOrderInfoAction() {
        if (!$this->CommonChecks()) return;
        
        $mDbBills = new model_Db_Bills();
        
        //Получаем информ. заказа по ID
        $res_bill_info = $mDbBills->GetByID ( $_POST['bill_id'], $this->user_rs['user_id'], !empty($_POST['good_info']));
        //#2877 В GetOrderInfo сделать получение utm партнера из заказа
        if((int)$res_bill_info['bill_id'] > 0)
            $res_bill_info['utm'] = App_Clicks_Utm::getUtmsByBillId((int)$res_bill_info['bill_id']);
        
        if(!empty($res_bill_info))
        {
            $res = array();
            $r = self::orderInfo($res_bill_info);
            if (!empty($_POST['payway_name'])) {
                $mDbPayways = new model_Db_Payways();
                $payway = $mDbPayways->getAllByName($this->user_rs['user_id'], $_POST['payway_name']);
                $r['payway_support_phone'] = $payway['support_phone'];
            }
            array_push($res, $r);
            $this->GenerateResponseResult(self::NO_ERROR, self::NO_ERROR_TEXT, $res);
            return;
        }
        else
        {
            $this->GenerateResponse(self::ERROR_ORDER_NOTFOUND, self::ERROR_ORDER_NOTFOUND_TEXT);
            return;
        }
    }
    
    /**
     * Получение списка заказов, сформированных или оплаченных в указанный промежуток времени
     * POST params:
     * date_s - в unixtime
     * date_e - в unixtime
     * paid - (bool) только оплаченные заказы
     * goods - (string or array) ID продуктов
     */
    public function GetOrdersAction() {
        if (!$this->CommonChecks()) return;
        
        $modBills = new model_Db_Bills();
        
        $orders = $modBills->GetByDateRange($this->user_rs['user_id'], array(
            'start' => $_POST['date_s'], 
            'end' => $_POST['date_e'], 
            'paid' => !empty($_POST['paid']),
            'goods' => $_POST['goods']
        ));
        
        if(!empty($orders) && count($orders) > 0)
        {
            $res = array();
            foreach ($orders as $order) {
                array_push($res, self::orderInfo($order));
            }
            
            $this->GenerateResponseResult(self::NO_ERROR, self::NO_ERROR_TEXT, $res);
        }
        else
        {
            $this->GenerateResponse(self::ERROR_ORDER_NO_ORDERS, self::ERROR_ORDER_NO_ORDERS_TEXT);
        }
        
        return;
    }
    
    //Получение кол-ва подписчиков по группе (для надписи "Нас уже...") (@copyright  2014 Ahmet Sampiev <sampiev@bk.ru> )
    public function GetCountSubscribeAction() {
        if (!$this->CommonChecks()) return;
        
        $modRassilkiLeads = new model_Db_RassilkiLeads();
        $modRassilki = new model_Db_Rassilki();
        
        switch ($_POST['group_name'])
        {
            case 'unique':    //Получение кол-во активных уникальных подписчиков
                $res_count_leads = $modRassilkiLeads->GetLeadsUnique($this->user_rs['user_id']);
                break;
            case 'alls':    //Получение кол-ва всех подписчиков
                $res_count_leads = $modRassilkiLeads->GetCountLeadsGroup($this->user_rs['user_id']);
                break;
            default:    //Получение кол-ва подписчиков по группе, если группа указана неверно, то ошибка
                $res_group_name = $modRassilki->GetByName(
                    $this->user_rs['user_id'],
                    $modRassilki->generateRassName($this->user_rs['user_name'], $_POST['group_name'])
                );
                if($res_group_name != null)
                    $res_count_leads = $modRassilkiLeads->GetCountLeadsGroup($this->user_rs['user_id'], $res_group_name['rass_id']);
                else
                    return $this->GenerateResponse(self::ERROR_GROUP_NOTFOUND, self::ERROR_GROUP_NOTFOUND_TEXT);
                break;
            case null:    //Если группа не указана
                return $this->GenerateResponse(self::ERROR_MISSING_PARAM, self::ERROR_MISSING_PARAM_TEXT);
        }
        return $this->GenerateResponseResult(self::NO_ERROR, self::NO_ERROR_TEXT, $res_count_leads);
    }

    // Получение списка активных доменов пользователя
    public function GetActiveDomainsListAction() {
        if (!$this->CommonChecks()) return;

        $items   = array();
        $user_id = $this->user_rs['user_id'];
        $mDbUD   = new model_Db_UserDomains();

        $res = $mDbUD->GetAllByConditions( false, $user_id );

        if( !empty( $res ) ) {
            foreach( $res as $item ) {
                $domain_status = URL::checkDomainLink( $item['domain'] );
                $passItem = false; // флаг - пропустить домен, т.к. он не прошел проверку

                switch( $domain_status ) {
                    case URL::DOMAIN_INVALID_NONEXISTENT:
                    case URL::DOMAIN_INVALID_IP:
                    case URL::DOMAIN_INVALID_DNSTYPE:
                        $passItem = true;
                        break;
                    case URL::DOMAIN_VALID_LINKED:
                    default:
                        $passItem = false;
                        break;
                }

                if( false === $passItem ) {
                    $items[] = $item['domain'];
                }
            }
        }

        if( !empty( $items ) ) {
            $this->GenerateResponseResult( self::NO_ERROR, self::NO_ERROR_TEXT, $items );
        }
        else
        {
            $this->GenerateResponse( self::ERROR_ACTIVE_DOMAINS_NOTFOUND, self::ERROR_ACTIVE_DOMAINS_NOTFOUND_TEXT );
        }
    }

    private function CommonChecks() {
        if(empty($this->user_rs)) {
            $this->GenerateResponse(self::ERROR_USER_INVALID, self::ERROR_USER_INVALID_TEXT);
            return false;
        }
        if($this->user_rs['user_status'] == 0) {
            $this->GenerateResponse(self::ERROR_USER_DESABLED, self::ERROR_USER_DESABLED_TEXT);
            return false;
        }
        if(count($_POST) == 0) {
            $this->GenerateResponse(self::ERROR_MISSING_PARAM, self::ERROR_MISSING_PARAM_TEXT);
            return false;
        }
        $hash = $_POST['hash'];
        if(empty($hash)) {
            $this->GenerateResponse(self::ERROR_NO_HASH, self::ERROR_NO_HASH_TEXT);
            return false;
        }
        unset($_POST['hash']);
        if(!$this->CheckHash($hash, $_POST)) {
            $this->GenerateResponse(self::ERROR_HASH_MISMATCH, self::ERROR_HASH_MISMATCH_TEXT);
            return false;
        }
        return true;
    }

    private function GenerateResponseResult($code, $text, $data) { 
        // Ответ с результирующими данными
        $secret = $this->user_rs['user_rps_key'];

        // Если это ответ на системный вызов - берем ключ из конфига
        if( isset( $_POST[ 'system_call'])){
            $secret = $this->_config->api->key;
        }

        $this->_set_body_params = json_encode(array(
                'error_code' => $code,
                'error_text' => $text,
                'result' => $data,
                'hash' => md5("$code::$text::$secret"),
        ));
        $this->show();
    }

    private function GenerateResponse($code, $text) {
        $secret = $this->user_rs['user_rps_key'];

        // Если это ответ на системный вызов - берем ключ из конфига
        if( isset( $_POST[ 'system_call'])){
            $secret = $this->_config->api->key;
        }

        $this->_set_body_params = json_encode(array(
            'error_code' => $code,
            'error_text' => $text,
            'hash' => md5("$code::$text::$secret"),
        ));
        $this->show();
    }
    
    private function CheckHash($hash, $params=array()) {
        $user_name = $this->user_rs['user_name'];
        $secret = $this->user_rs['user_rps_key'];

        // Если это системный вызов - берем ключ из конфига
        if( isset( $params[ 'system_call'])){
            $secret = $this->_config->api->key;
        }

        $params = http_build_query($params);
        $params = "{$params}::{$user_name}::{$secret}";

        if ($hash == md5($params)) return true;
        else return false;
    }
    
    private function CheckEmail(&$email) {
        $email = strtolower($email);
        return preg_match("/[\\w-\\.]+@([\\w-]+\\.)+[\\w-]{2,4}/",$email);
    }
    
    public static function orderInfo($order) {
        $bill_domain = $order['bill_domain'];
        if (!empty($bill_domain)) {
            if ((substr($bill_domain, 0, 7) != "http://") && (substr($bill_domain, 0, 8) != "https://")) {
                $bill_domain = "http://" . $bill_domain;
            }
        }
        $res = array(
            'id' => $order['bill_id']
            , 'first_name' => $order['bill_first_name']
            , 'last_name' => $order['bill_surname']
            , 'middle_name' => $order['bill_middle_name']
            , 'email' => $order['bill_email']
            , 'phone' => $order['bill_phone']
            , 'city' => $order['bill_city']
            , 'country' => $order['bill_country']
            , 'address' => $order['bill_address']
            , 'region' => $order['bill_region']
            , 'postalcode' => $order['bill_postal_code']
            , 'created' => $order['bill_created']
            , 'pay_status' => $order['bill_pay_status']
            , 'paid' => $order['bill_paid']
            , 'type' => $order['bill_type']
            , 'payway' => $order['bill_payway']
            , 'comment' => $order['bill_comment']
            , 'domain' => $order['bill_domain']
            , 'link' => $bill_domain."/bill/?id=".$order['bill_id']."&crc=".ShopLinks::billCrc($order['bill_id'])
        );
        
        if(count($order['utm']) > 0)
            $res['utm'] = $order['utm'];

        if(!empty($order['good_ids'])) $res['good_ids'] = $order['good_ids'];
        if(!empty($order['good_count'])) $res['good_count'] = $order['good_count'];
        
        $mDbBillGoods = new model_Db_BillGoods;
        if( !empty($order['good_sum'])) $res['price'] = $order['good_sum'];
        else $res[ 'price'] = $mDbBillGoods->GetBillSum( $order[ 'bill_id']);
        
        // Информация по предоплате для счета (используется при оплате через justClick)
        if(!empty($order['prepayment_enabled'])) {
            $config = ShopConfig::Get($order['owner_id']);
            $prepayment_minsum = !empty($config['prepayment_minsum']) ? $config['prepayment_minsum'] : 1000;
            $goods = ShopBill::BillGoods($order['bill_id']);
            if(!empty($goods)) {
                foreach ($goods as $good) {
                    // Только если к продукту разрешена предоплата
                    if(!empty($good['good_prepayment_enabled'])) {
                        // Предоплата действует на весь заказ, но выбирается максимальная сумма
                        if($prepayment_minsum < $good['good_prepayment_minsum']) {
                            $prepayment_minsum = $good['good_prepayment_minsum'];
                        }
                    }
                }
            }
            
            $mDbBillPrepayments = new model_Db_BillPrepayments();
            $paid_sum = $mDbBillPrepayments->GetTotalByBill($order['bill_id']);
            $res['bill_sum_topay'] = $res['price'] - $paid_sum;
            
            $res['prepayment_enabled'] = $order['prepayment_enabled'];
            $res['prepayment_minsum'] = $prepayment_minsum;
        } else $res['bill_sum_topay'] = $res['price'];

        return $res;
    }
    
    /**
     * Ф-я, блокирующая запрос по IP
     * @param int $count - количество блокировок
     * @param int $time - время разблокировки в секундах
     */
    private function checkIP($count, $time) {
        
        // Проверяем по базе заблокированный IP
        $checkIP = $this->_model->Db_IpBlock->checkIP($_SERVER['REMOTE_ADDR']);
        
        if(isset($checkIP['count']) && isset($checkIP['time']) && $checkIP['count'] >= $count) {
            // Если не прошло достаточно времени для снятия блокировки
            if((time() - intval($checkIP['time'])) <= $time) {
                $this->blockIP($checkIP); // Обновляем время
                $this->GenerateResponse(self::ERROR_IP_BLOCK, self::ERROR_IP_BLOCK_TEXT . $_SERVER['REMOTE_ADDR']);
                return false;
            } else {
                // Время прошло, стираем старую блокировку
                $this->_model->Db_IpBlock->delete('id = ' . $checkIP['id']);
                return null;
            }
        }
        
        return $checkIP;
    }
    
    private function blockIP($checkIP = null) {
        
        $this->_model->Db_IpBlock->blockIP($_SERVER['REMOTE_ADDR']);
    }
    
    /**
     * Для RussianPostService - метод, позволяющий по запросу обновлять данные
     * регистрации пользователя RPS в JC
     */
    public function rps_updateAction() {

        // Настройки интеграции - после завершения интеграции перенести в конфиг
        $regpsQuery['login'] = 'zverev12';
        $regpsQuery['uid'] = 651;
        $rps_key = '123456789';

        if(!isset($_POST['json'])) {
            echo ('Access Denied!');
            return;
        }

        $json = json_decode($_POST['json'], true);

        if(empty($json['fields']) || !isset($json['fields']['login'])) {
            echo('Access Denied!');
            return;
        }

        $fields = $json['fields'];
        $user = ShopUser::GetByName($fields['login'], true);

        if(empty($user)) {
            echo('User Not Found!');
            return;
        }

        $settings = ShopConfig::Get($user['user_id']);

        if(empty($settings)) {
            echo('User don`t have shop!');
            return;
        }

        // Проверяем подпись
        $regpsQuery['fileds'] = $fields;
        $hash = Utils::getRPSHash($regpsQuery, $rps_key);

        if($hash != $json['hash'] || $json['login'] != $regpsQuery['login'] || $json['uid'] != $regpsQuery['uid']) {
            echo('Access Denied!');
            return;
        }

        // Подпись подтверждена, данные из верного источника
        // Если данные пришли из RPS, значит они проверены уже
        $fields['errors'] = array('access_denied' => true);

        // -4 - редактирование блокировано, но данные не подтверждены
        // -2 - данные подтверждены
        $status = !empty($fields['tolerance']) ? -2 : -4; 

        if(isset($fields['tolerance'])) unset($fields['tolerance']);

        $mDbConfig = new model_Db_Config();
        $mDbConfig->updateConf($user['user_id'], array(
            'regps_data' => serialize($fields),
            'regps_status' => $status
        ));

        echo('Success!');
        return;
    }
    
    public function GetresponsecallbackAction() {
        
        $mDbRassilki = new model_Db_Rassilki();
        $mDbLeadInfo = new model_Db_LeadInfo();
        
        $user_id    = $this->user_rs[ 'user_id'];
        $user_name    = $this->user_rs[ 'user_name'];
        $no_activation = !empty($this->user_rs['user_noactivation']);
        
        $campaign_id = urldecode($_GET['CAMPAIGN_ID']);
        $contact_email = urldecode($_GET['contact_email']);
        $contact_name = urldecode($_GET['contact_name']);
        $contact_origin = urldecode($_GET['contact_origin']);
        
        if(isset($_GET['action']) && $_GET['action'] == 'subscribe') {
            // Достаем все рассылки у нас по CAMPAIGN_ID
            $rassilki = $mDbRassilki->GetByGetResponseCampaign($campaign_id, $user_id);
            $rassIds = array();
            $rassArr = array();
            if(!empty($rassilki)) {
                foreach ($rassilki as $rass) {
                    if ( $rass[ 'rass_can_subscribe'] == 2) continue; // подписка запрещена (стоит уведомить админа чтобы проверил настройки)
                    // Если в нашей группе включена обратная подписка
                    if(!empty($rass['getresponse_subscribe_gr'])) {
                        
                        $res = App_Leads::Subscribe(
                            $rass['rass_id']
                            , array(
                                'email'        => $contact_email
                                , 'name'    => $contact_name
                            )
                            , array(
                                // Статус подписчика либо 2 - неактивирован, либо 1 - активирован
                                'active'    => ($no_activation) ? 1 : 2
                                , 'tag'        => 'getresponse_' . $contact_origin
                            )
                            , $errors
                            , $LEAD_ID
                            , true
                        );
                        
                        if( !$res) continue;
                        $rassIds[] = $mDbRassilki->extractRassName($rass['rass_name'], $user_name);
                        $rassArr[] = $rass;
                    }
                }

                if( $no_activation === false){
                    $lead_data = array( 'lead_name' => $contact_name, 'lead_email' => $contact_email);
                    App_Leads::NeedConfirm($rassIds, $lead_data, false, $rassArr);
                }
                
                echo('Subscribe synchronized');
                return;
            } else {
                echo('No mailings to synchronize');
                return;
            }
            
        } elseif (isset($_GET['action']) && $_GET['action'] == 'unsubscribe') {
            
            // Достаем все рассылки у нас по CAMPAIGN_ID
            $rassilki = $mDbRassilki->GetByGetResponseCampaign($campaign_id, $user_id);
            $lead_id = $mDbLeadInfo->GetLeadIdByEmail($contact_email, $user_id);
            if(!empty($rassilki) && !empty($lead_id)) {
                foreach ($rassilki as $rass) {
                    // Если в нашей группе включена обратная отписка
                    if(!empty($rass['getresponse_unsubscribe_gr'])) {
                        App_Leads::Unsubscribe($rass['rass_id'], $lead_id);
                    }
                }
                
                echo('Unsubscribe synchronized');
                return;
            } else {
                echo('No mailings to synchronize');
                return;
            }
            
        }
        
        echo('Access denied');
        return;
    }

    /**
     * возвращает утм-метки по id клика
     *
     * @param $_POST['click_id'] int id_клика в JC_utm_clicks (www-shard02-a)
     *
     * @return array
     */
    public function getUtmByClickAction()
    {
        if (!$this->CommonChecks())
            return;

        $data = $_POST;
        $res = [
            'click_medium'  => '',
            'click_source'  => '',
            'click_content' => '',
            'click_campaign'=> '',
            'click_term'    => '',
        ];

        if($data['click_id'] > 0){
            $mDbUtmClicks = new model_Db_UtmClicks;
            $row = $mDbUtmClicks->find($data['click_id'])->current();
            if($row != false){
                $res['click_medium']   = $row->click_medium;
                $res['click_source']   = $row->click_source;
                $res['click_content']  = $row->click_content;
                $res['click_campaign'] = $row->click_campaign;
                $res['click_term']     = $row->click_term;
            }
        }

        $this->GenerateResponseResult(self::NO_ERROR, self::NO_ERROR_TEXT, $res);
        return;
    }

    /**
     * Устанавливаем утм-клик через API, фикс для justclick.org
     */
    public function setUtmClicksAction()
    {
        if (!$this->CommonChecks())
            return;

        $res = App_Clicks_Utm::AddUtmClicksInDB($_POST['data'], $_POST['userId'], $_POST['timeNow']);

        $this->GenerateResponseResult(self::NO_ERROR, self::NO_ERROR_TEXT, $res);
        return;
    }

    /**
     * Закрепление parent_user_id при регистрации нового юзера через утм-метки
     *
     * @param $_POST['click_id'] int id_клика в JC_utm_clicks (www-shard02-a)
     * @param $_POST['aff_click_id'] int id_клика в JC_aff_clicks (www-shard02-a)
     *
     * @return array
     */
    public function RegisterUserAction()
    {
        if (!$this->CommonChecks())
            return;

        $data = $_POST;
        $res = array(
            'utm_click_id'  => 0,
            'aff'            => 0,
            'aff_source'    => '',
        );

        if($data['click_id'] > 0){
            $mDbUtmClicks = new model_Db_UtmClicks;
            $row = $mDbUtmClicks->find($data['click_id'])->current();
            if($row != false && $row->shop_id == 1){
                $res['utm_click_id'] = $row->click_id;
            }
        }
        //чтобы в JC_users был parent_user_id
        if($data['aff_click_id'] > 0){
            $mDbAC = new model_Db_AffClicks();
            $row = $mDbAC->find($data['aff_click_id'])->current();
            if($row != false && $row->shop_id == 1){
                $res['aff'] = $row->partner_id;
                $res['aff_source'] = $row->click_source;
            }
        }

        $this->GenerateResponseResult(self::NO_ERROR, self::NO_ERROR_TEXT, $res);
        return;
    }
    /**
     * Закрепление партнера за admin-ом в JC_partner_vendors на shard02-a
     *
     * @param $_POST['click_id'] int id_клика в JC_utm_clicks (www-shard02-a)
     * @param $_POST['aff_click_id'] int id_клика в JC_aff_clicks (www-shard02-a)
     *
     * @return array
     */
    public function AddPartnerForAdminAction()
    {
        if (!$this->CommonChecks())
            return;

        $data = $_POST;

        $appP = new App_Partners();
        $r = $appP->makePartner($data['user_id'], 1, array(
                'aff' => $data['aff'],
                'click' => $data['click'],
            )
        );

        $this->GenerateResponseResult(self::NO_ERROR, self::NO_ERROR_TEXT, $r);
        return;
    }
    /**
     * Возвращает партнеров 2-го уровня при пополнении/оплате тарифа (admin в JC_partner_vendors на shard02-a)
     *
     * @param $_POST['click_id'] int id_клика в JC_utm_clicks (www-shard02-a)
     * @param $_POST['aff_click_id'] int id_клика в JC_aff_clicks (www-shard02-a)
     *
     * @return array
     */
    public function GetPaffForAdminAction()
    {
        if (!$this->CommonChecks())
            return;

        $data = $_POST;
        $r = MLM::getPaffs($data['user_id'], $data['aff'], $data['email'], $data['isString']);

        $this->GenerateResponseResult(self::NO_ERROR, self::NO_ERROR_TEXT, $r);
        return;
    }

    /**
     * Возвращает информацию о товарах по их имени и username автора
     *
     * @param $_POST['goods'] array - массив имен товаров БЕЗ имени автора
     * @param $_POST['username'] string - username автора
     *
     * @return array
     */
    public function GetGoodsInfoAction()
    {
        if (!$this->CommonChecks()) {
            return;
        }
        if (empty($_POST) || empty($_POST['goods']) || empty($_POST['username'])) {
            $this->GenerateResponse(self::ERROR_MISSING_PARAM, self::ERROR_MISSING_PARAM_TEXT);
            return;
        }
        else {
            $res = [];
            foreach ($_POST['goods'] as $goodName) {
                $res[$_POST['username'].'_'.$goodName] = ShopGood::GetByName($_POST['username'].'_'.$goodName);
            }
            if (!empty($res)) {
                $this->GenerateResponseResult( self::NO_ERROR, self::NO_ERROR_TEXT, $res );
                return;
            } else {
                $this->GenerateResponse( self::ERROR_PRODUCT_NOTFOUND, self::ERROR_PRODUCT_NOTFOUND_TEXT );
                return;
            }
        }
    }

    /**
     * Возвращает название самого свежего действующего товара админа - тарифа. Нужна для теста цены за тариф #3792
     * @return bool
     */
    public function GetFreshTariffsAction()
    {
        if (!$this->CommonChecks()) {
            return false;
        }

        // Вызов может быть только системным
        if(!isset($_POST['system_call'])) {
            self::GenerateResponse(self::ERROR_WRONG_DATA, self::ERROR_WRONG_DATA_TEXT);
            return false;
        }
        if (empty($_POST) || empty($_POST['tariffs']) || empty($_POST['username'])) {
            $this->GenerateResponse(self::ERROR_MISSING_PARAM, self::ERROR_MISSING_PARAM_TEXT);
            return false;
        }
        else {
            $user = ShopUser::GetByName($_POST['username']);
            $tariffGoods = [];
            foreach ($_POST['tariffs'] as $tariff) {
                $tariffGoodName = (empty($user["{$tariff}_good_name"]) ? "admin_justclick_{$tariff}_1" : $user["{$tariff}_good_name"]);
                $tariffGood = ShopGood::GetByName($tariffGoodName);
                if (empty($tariffGood) || empty($user)) {
                    // такой товар в БД не найден, м.б. удалил админ ИЛИ новый юзер, его ещё нет в БД - возьмем самый свежий
                    $tariffGood = ShopUser::getTariffGood($tariff);
                }
                $tariffGoods[$tariff] = $tariffGood;
            }

            if (!empty($tariffGoods)) {
                $this->GenerateResponseResult( self::NO_ERROR, self::NO_ERROR_TEXT, $tariffGoods );
                return false;
            } else {
                $this->GenerateResponse( self::ERROR_PRODUCT_NOTFOUND, self::ERROR_PRODUCT_NOTFOUND_TEXT );
                return false;
            }
        }
    }

    /**
     * Получение статистики партнера.
     * POST params:
     * partner - логин партнера
     * dateFrom - нижний предел временного интервала (Y-m-d)
     * dateTo - верхний предел временного интервала (Y-m-d)
     */
    public function GetPartnerStatsAction()
    {
        $mDbUsers       = new \model_Db_Users();
        $mDbPartnerStat = new \model_Db_PartnerStat();

        if (!$this->CommonChecks()) {
            return;
        }

        if (!isset($_POST['partner'])) {
            $this->GenerateResponse(self::ERROR_MISSING_PARAM, self::ERROR_MISSING_PARAM_TEXT);
            return;
        }

        if (!($partner = $mDbUsers->GetByName($_POST['partner']))) {
            $this->GenerateResponse(self::ERROR_PARTNER_NOTFOUND, self::ERROR_PARTNER_NOTFOUND_TEXT);
            return;
        }

        $response = [
            'earned_total'      => 0,
            'topay_total'       => 0,
            'clicks_total'      => 0,
            'leads_total'       => 0,
            'bills_total'       => 0,
            'partners_total'    => 0,
        ];

        $partnerStats = $mDbPartnerStat->GetStatsForIds(
            false,
            $this->user_rs['user_id'],
            [$partner['user_id']],
            [$_POST['date_from'], $_POST['date_to']]
        );

        if (!empty($partnerStats)) {
            foreach ($response as $field => $null) {
                $response[$field] = $partnerStats[0][$field];
            }
        }

        $this->GenerateResponseResult(self::NO_ERROR, self::NO_ERROR_TEXT, $response);
        return;
    }

}
