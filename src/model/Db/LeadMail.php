<?php
/**
 * model/Db/LeadMail.php, model_Db_LeadMail class, database interface
 *
 * PHP versions 5
 * 
 * @copyright  2013 Alexandr Schaulin <ashaulin@justclick.ru> 
 */
 
class model_Db_LeadMail extends App_Db_Table_Abstract 
{
    protected $_name    = 'JC_lead_mail';
    
    public function deleteMail($mail_id) {
        $sid = (int)$mail_id;
        $where = "mail_id = $sid";

        $this->delete($where);
        return true;
    }
    
    public function Add($lead_id, $body, $subj, $from_email, $from_name, $time) {
        return parent::insert(array(
                    'lead_id' => $lead_id,
                    'message_time' => $time,
                    'message_from' => $from_email,
                    'message_from_name' => $from_name,
                    'message_subject' => $subj,
                    'message_body' => $body,
                ));
    }
        
    public function GetAllByLead($lead_id) {
        $lid = (int)$lead_id;
        // получаем все прочие письма отправленные этому пользователю
        $select = "SELECT LM.mail_id as mail_id, LM.message_time as time, 'e' as type, 0 as variant
                FROM {$this->_name} AS LM 
                WHERE LM.lead_id=$lid";
        
        return  $this->_db->fetchAll($select);
    }
    
    public function GetMail($mail_id) {
        $mail_id = (int)$mail_id;
        // получаем конкретное письмо
        $select = $this->_db->select()
            ->from($this->_name)
            ->where('mail_id = ?', $mail_id);
        return $this->_db->fetchRow($select);
    }
    
}
