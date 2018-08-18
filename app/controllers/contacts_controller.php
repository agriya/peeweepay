<?php
class ContactsController extends AppController
{
    var $name = 'Contacts';
    var $components = array(
        'Email',
        'RequestHandler'
    );
    var $uses = array(
        'Contact',
        'EmailTemplate'
    );
    function add()
    {
        if (!empty($this->data)) {
            $this->Contact->set($this->data);
            if ($this->Contact->validates()) {
                $ip = $this->RequestHandler->getClientIP();
                $this->data['Contact']['ip'] = $ip;
                $this->data['Contact']['user_id'] = $this->Auth->user('id');
                $this->Contact->save($this->data, false);
                $emailFindReplace = array(
                    '##SITE_NAME##' => Configure::read('site.name') ,
                    '##FIRST_NAME##' => $this->data['Contact']['first_name'],
                    '##LAST_NAME##' => !empty($this->data['Contact']['last_name']) ? ' ' . $this->data['Contact']['last_name'] : '',
                    '##FROM_EMAIL##' => $this->data['Contact']['email'],
                    '##FROM_URL##' => Router::url(array(
                        'controller' => 'contacts',
                        'action' => 'add'
                    ) , true) ,
                    '##SITE_ADDR##' => gethostbyaddr($ip) ,
                    '##IP##' => $ip,
                    '##TELEPHONE##' => $this->data['Contact']['telephone'],
                    '##MESSAGE##' => $this->data['Contact']['message'],
                    '##SUBJECT##' => $this->data['Contact']['subject'],
                    '##POST_DATE##' => date('F j, Y g:i:s A (l) T (\G\M\TP)') ,
                    '##CONTACT_URL##' => Router::url(array(
                        'controller' => 'contacts',
                        'action' => 'add'
                    ) , true) ,
                    '##SITE_URL##' => Router::url('/', true) ,
                );
                $email = $this->EmailTemplate->selectTemplate('Contact Us');
                // send to contact admin email
                $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
                $this->Email->from = strtr($this->Email->from, $emailFindReplace);
                $this->Email->replyTo = strtr($this->Email->replyTo, $emailFindReplace);
                $this->Email->to = Configure::read('site.contact_email');
                $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                $this->set('success', 1);
            }
        }
        $this->pageTitle = __l('Contact Us');
    }
}
?>