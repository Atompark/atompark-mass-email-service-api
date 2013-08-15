<?php

class ApiEmailService {

    const API_VERSION = '3.0';
    const BASE_URL = 'http://atompark.com/api/email/3.0/';
    const CURL = 1; // curl request
    const SOURCE_ENCODING = 'UTF8';

    private $privateKey = NULL;
    private $publicKey = NULL;

    /**
     * @param string $publicKey  Public key
     * @param string $privateKey Private key
     */
    function __construct($publicKey, $privateKey) {
        if (empty($publicKey))
            $this->error('Empty private key');
        if (empty($privateKey))
            $this->error('Empty public key');

        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        return $this;
    }

    /* AddressBook */
    /* ====================================== */

    /**
     * Create address book
     * @param string $address_book_name  Address-book name
     */
    public function addAddressBook($address_book_name) {
        if (!empty($address_book_name)) {
            $params = array();
            $params['name'] = $address_book_name;
            return $this->getResult(__FUNCTION__, $params);
        }
        return $this->error('Empty address-book name');
    }

    /**
     * Remove address book
     * @param int $address_book_id  Address-book id
     */
    public function delAddressBook($address_book_id) {
        if (!empty($address_book_id)) {
            $params = array();
            $params['id'] = $address_book_id;
            return $this->getResult(__FUNCTION__, $params);
        }
        return $this->error('Empty address-book name');
    }

    /**
     * Get address book info
     * @param int $address_book_id  Address-book id
     */
    public function getAddressBook($address_book_id = NULL) {
        $params = array();
        $params['id'] = $address_book_id;
        return $this->getResult(__FUNCTION__, $params);
    }

    /* Addresses */
    /* ====================================== */

    /**
     * Add addresses in address-book
     * @param int|array    $list_id Address book id
     * @param array  $emails Emails addresses
     * @param int|array  $labels Labels
     * @param array  $v Variables
     */
    public function addAddresses($id_list, array $emails, $v = array(), $labels = NULL) {
        if (isset($id_list) && is_array($emails) && count($emails)) {
            $params = array();
            if (!is_array($id_list)) {
                $ab_id = (int) $id_list;
                $id_list = array();
                $id_list[] = $ab_id;
            }
            /* id list */
            if (is_array($id_list)) {
                foreach ($id_list as $list) {
                    $params['id_list'][] = $list;
                }
            } else {
                $params['id_list'][] = $id_list;
            }
            /* emails */
            foreach ($emails as $email)
                $params['email'][] = $email;
            /* labels */
            if (!empty($labels)) {
                if (!is_array($labels)) {
                    $labels = explode(',', $labels);
                    $labels = array_map('trim', $labels);
                }
                foreach ($labels as $label) {
                    $params['labels'][] = $label;
                }
            }
            /* variables */
            if (is_array($v) && count($v)) {
                foreach ($v as $label) {
                    $params['v'][] = $label;
                }
            }
            return $this->getResult(__FUNCTION__, $params);
        }
        return $this->error('Empty address-book id or emails');
    }

    /**
     * Activate emails in address-book
     * @param int    $address_book_id Address book id
     * @param text   $description Description for emails
     */
    public function activateEmails($address_book_id, $description) {
        if (isset($address_book_id)) {
            if (isset($description)) {
                $params = array();
                $params['id'] = $address_book_id;
                $params['description'] = $description;
                return $this->getResult(__FUNCTION__, $params);
            }
            return $this->error('Empty description');
        }
        return $this->error('Empty address-book id');
    }

    /**
     * Change email status
     * @param int $address_book_id Address book id
     * @param string $email Email addresses
     * @param int $status Status
     */
    public function changeEmailStatus($address_book_id, $email, $status = 0) {
        if (isset($address_book_id)) {
            if (isset($email)) {
                $params = array();
                $params['id'] = $address_book_id;
                $params['email'] = $email;
                $params['status'] = $status;
                return $this->getResult(__FUNCTION__, $params);
            }
            return $this->error('Empty email');
        }
        return $this->error('Empty address-book id');
    }

    /**
     * Remove email
     * @param int $address_book_id Address book id
     * @param string $email Email addresses
     */
    public function delEmail($address_book_id, $email) {
        if (isset($address_book_id)) {
            if (isset($email)) {
                $params = array();
                $params['id'] = $address_book_id;
                $params['email'] = $email;
                return $this->getResult(__FUNCTION__, $params);
            }
            return $this->error('Empty email');
        }
        return $this->error('Empty address-book id');
    }

    /* Campaign */
    /* ====================================== */

    /**
     * Create campaign.
     * @param string $sender_name  Sender name
     * @param string $sender_email Sender email
     * @param string $subject Subject
     * @param text   $body Body
     * @param int    $list_id Address book id
     * @param string $name Campaign name
     * @param string $labels '1,2,3'
     */
    public function createCampaign($sender_name, $sender_email, $subject, $body, $list_id, $name = '', $labels = '') {
        if (isset($sender_name) && isset($sender_email) && isset($subject) && isset($body) && isset($list_id)) {
            $params = array();
            $params['name'] = $name;
            $params['sender_name'] = $sender_name;
            $params['sender_email'] = $sender_email;
            $params['subject'] = $subject;
            $params['body'] = base64_encode($body);
            $params['list_id'] = $list_id;
            $params['labels'] = $labels;
            return $this->getResult(__FUNCTION__, $params);
        }
        return $this->error('Empty parameters');
    }

    /**
     * Get campaign info
     * @param int $start Start position
     * @param int $offset Limit count items
     */
    public function getCampaign($start = NULL, $offset = NULL) {
        $params = array();
        $params['start'] = $start;
        $params['offset'] = $offset;
        return $this->getResult(__FUNCTION__, $params);
    }

    /**
     * Get campaign statistics
     * @param int $campaign_id Campaign id
     */
    public function getCampaignStats($campaign_id) {
        if (isset($campaign_id)) {
            $params = array();
            $params['id'] = $campaign_id;
            return $this->getResult(__FUNCTION__, $params);
        }
        return $this->error('Empty campaign id');
    }

    /**
     * Campaign addresses info
     * @param int $campaign_id Campaign id
     * @param int $start Start position
     * @param int $offset Limit count items
     */
    public function getCampaignDeliveryStats($campaign_id, $start = NULL, $offset = NULL) {
        if (isset($campaign_id)) {
            $params = array();
            $params['id'] = $campaign_id;
            $params['start'] = $start;
            $params['offset'] = $offset;
            return $this->getResult(__FUNCTION__, $params);
        }
        return $this->error('Empty campaign id');
    }

    /* Sensders */
    /* ====================================== */

    /**
     * Get senders
     * @param int $start Start position
     * @param int $offset Limit count items
     */
    public function getSender($start = NULL, $offset = NULL) {
        $params = array();
        $params['start'] = $start;
        $params['offset'] = $offset;
        return $this->getResult(__FUNCTION__, $params);
    }

    /**
     * Add sender
     * @param string $sender_name  Sender name
     * @param string $sender_email Sender email
     */
    public function addSender($sender_name, $sender_email) {
        if (isset($sender_name) && isset($sender_email)) {
            $params['sender_name'] = $sender_name;
            $params['sender_email'] = $sender_email;
            return $this->getResult(__FUNCTION__, $params);
        }
        return $this->error('Empty parameters');
    }

    /**
     * Get sender activate code
     * @param string $sender_email Sender email
     */
    public function getSenderActivateCode($sender_email) {
        if (isset($sender_email)) {
            $params['sender_email'] = $sender_email;
            return $this->getResult(__FUNCTION__, $params);
        }
        return $this->error('Empty sender email');
    }

    /**
     * Activate sender
     * @param string $sender_email  Sender name
     * @param string $code Activate code
     */
    public function activateSender($sender_email, $code) {
        if (isset($sender_email) && isset($code)) {
            $params['sender_email'] = $sender_email;
            $params['code'] = $code;
            return $this->getResult(__FUNCTION__, $params);
        }
        return $this->error('Empty parameters');
    }

    /**
     * Remove sender
     * @param string $sender_email Sender email
     */
    public function delSender($sender_email) {
        if (isset($sender_email)) {
            $params['sender_email'] = $sender_email;
            return $this->getResult(__FUNCTION__, $params);
        }
        return $this->error('Empty sender email');
    }

    /* Balance */
    /* ====================================== */

    /**
     * Get balance user
     * @param string $currency Currency ( 'USD', 'EUR', 'GBP', 'RUR', 'RUB', 'UAH' )
     */
    public function getUserBalance($currency = 'USD') {
        $currency = strtoupper($currency);
        $cur = array('USD', 'EUR', 'GBP', 'RUR', 'RUB', 'UAH');
        if (in_array($currency, $cur)) {
            $params['currency'] = $currency;
            return $this->getResult(__FUNCTION__, $params);
        }
        return $this->error('No correct currency');
    }

    /* Balance */
    /* ====================================== */

    /**
     * Get user labels
     */
    public function getLabels() {
        $params = array();
        return $this->getResult(__FUNCTION__, $params);
    }

    /**
     * Create labels
     * @param string $label_name Label name
     */
    public function addLabels($label_name) {
        if (isset($label_name)) {
            $params['label_name'] = $label_name;
            return $this->getResult(__FUNCTION__, $params);
        }
        return $this->error('Empty label name');
    }

    /**
     * Remove labels
     * @param string $label_name Label name
     */
    public function delLabels($label_name) {
        if (isset($label_name)) {
            $params['label_name'] = $label_name;
            return $this->getResult(__FUNCTION__, $params);
        }
        return $this->error('Empty label name');
    }

    /* Blacklist */
    /* ====================================== */

    /**
     * Get email blacklist
     * @param int $start Start position
     * @param int $offset Limit count items
     */
    public function getBlackList($start = NULL, $offset = NULL) {
        $params = array();
        $params['start'] = $start;
        $params['offset'] = $offset;
        return $this->getResult(__FUNCTION__, $params);
    }

    /**
     * Add email to blacklist
     * @param string $email 
     */
    public function addBlackList($email, $comment = '') {
        if (isset($email)) {
            $params['email'] = $email;
            $params['comment'] = $comment;
            return $this->getResult(__FUNCTION__, $params);
        }
        return $this->error('Empty email');
    }

    /**
     * Remove email blacklist
     * @param string $email
     */
    public function delBlackList($email) {
        if (isset($email)) {
            $params['email'] = $email;
            return $this->getResult(__FUNCTION__, $params);
        }
        return $this->error('Empty email');
    }

    /* ====================================== */
    /* private methods */
    /* ====================================== */

    private function getResult($action, $params = array(), $response_mode = self::CURL) {
        if (!empty($action)) {
            if (self::SOURCE_ENCODING != 'UTF8') {
                if (function_exists('iconv')) {
                    array_walk_recursive($params, array($this, 'iconv'));
                } else if (function_exists('mb_convert_encoding')) {
                    array_walk_recursive($params, array($this, 'mb_convert_encoding'));
                }
            }
            $control_sum = $this->getControlSum($action, $params);
            $params_url = '';
            if (is_array($params) && count($params))
                $params_url = '&' . http_build_query($params);
            if (!$response_mode) {
                $url = self::BASE_URL . $action . '?key=' . $this->publicKey . '&sum=' . $control_sum . $params_url;
                return json_decode(file_get_contents($url), TRUE);
            } else { // CURL
                if (function_exists('curl_version')) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_CRLF, TRUE);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, 'key=' . $this->publicKey . '&sum=' . $control_sum . $params_url);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_URL, self::BASE_URL . $action); //
                    $res = curl_exec($ch);
                    curl_close($ch);
                    return json_decode($res, TRUE);
                } else {
                    return $this->error('No detected CURL');
                }
            }
            return $this->error('Empty request url');
        }
        return $this->error('Empty param: action name');
    }

    private function getControlSum($action, array $arrayRequest) {
        if (!empty($action)) {
            $arrayRequest ['version'] = (!empty($arrayRequest ['version'])) ? trim($arrayRequest ['version']) : self::API_VERSION;
            $arrayRequest ['key'] = (!empty($arrayRequest ['key'])) ? trim($arrayRequest ['key']) : $this->publicKey;
            $arrayRequest ['action'] = $action;
            ksort($arrayRequest);
            $sum = '';
            foreach ($arrayRequest as $v)
                $sum.=$v;
            $res = $sum.=$this->privateKey;
            return md5($res);
        }
        return $this->error('Empty param: action name');
    }

    private function error($message) {
        die($message);
    }

    private function iconv(&$Value, $Key) {
        $Value = iconv(self::SOURCE_ENCODING, 'UTF8//IGNORE', $Value);
    }

    private function mb_convert_encoding(&$Value, $Key) {
        $Value = mb_convert_encoding($Value, 'UTF8', self::SOURCE_ENCODING);
    }

}