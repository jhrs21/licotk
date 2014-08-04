<?php

/**
 * email actions.
 *
 * @package    elperro
 * @subpackage email
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

require_once sfConfig::get('sf_lib_dir') . '/vendor/sendgrid-php/SendGrid_loader.php';

class emailActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {

        $test = Doctrine_Core::getTable('Promo')->findByStatus('active');
        $promos = array();

        foreach ($test as $t) {
            $promos[$t->getId()] = $t->getName();
        }

        $this->form = new epEmailReminderForm(array(), array('promos' => $promos));
        $this->form2 = new epEmailNewUsersForm();

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));
            $this->form2->bind($request->getParameter($this->form2->getName()));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $promo = $values['promo'];

                $routing = sfContext::getInstance()->getRouting();

                $this->redirect($routing->generate('email_send_prize_remainder', array('id' => $promo)));
            }

            if ($this->form2->isValid()) {
                $values = $this->form2->getValues();
                $email_list = $values['emails'];
                $this->redirect('@email_send?query=3&email_list=' . $email_list);
            }
        }
    }

    public function executeNewCustomEmail(sfWebRequest $request) {
        $this->form = new epMailMessageForm();
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $email = $this->form->save();
                
                $this->redirect('email/sendEmail?id=' . $email->getId());
            }
        }
        
        $this->setLayout('layout_blueprint');
    }
    
    public function executeEditCustomEmail(sfWebRequest $request) {
        $message = $this->getRoute()->getObject();
        
        $this->form = new epMailMessageForm($message);
        
        if ($request->isMethod(sfWebRequest::PUT)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $email = $this->form->save();
                
                $this->redirect('email/sendEmail?id=' . $email->getId());
            }
        }
        
        $this->setLayout('layout_blueprint');
    }
    
    public function executeSendEmail(sfWebRequest $request) {
        $this->email = $this->getRoute()->getObject();
        $this->form = new epUserFormFilter();
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $query = $this->form->getQuery();
                
                $result = $query->execute(array(), Doctrine::HYDRATE_ARRAY_SHALLOW);
                
                $this->email->setMaxReach(count($result));
                $this->email->save();
                
                $this->sendMail($this->email, $result);
            }
        }
        
        $this->setLayout('layout_blueprint');
    }

    public function executeSendRegistrationRemainderMail(sfWebRequest $request) {
        $query = Doctrine_Query::create()
                ->from('UserProfile up')
                ->leftJoin('up.User u')
                ->where('u.pre_registered=1');

        $preregs = $query->execute();
        
        if (!$preregs->count()) {
            $this->redirect('email_nopreregs');
        }
        
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_ep_domain','lealtag.com'),
                ));
        
        $emailParams = array(
            'subject'       => sfConfig::get('app_emails_registration_remainder_subject'),
            'teaser'        => sfConfig::get('app_emails_registration_remainder_teaser'),
            'to'            => array(),
            'html'          => 'email/emailPreregistered',
            'substitutions' => array('%ROUTE%' => array()),
            'category'      => array('massive', 'reminder', 'pre-register'),
        );
        
        $count = 0;
        foreach ($preregs as $prereg) {
            $emailParams['to'][] = $prereg->getEmail();
            $emailParams['substitutions']['%ROUTE%'][] = $routing->generate('user_complete_register', array('validate' => $prereg->getValidate()), true);
            
            $count++;

            if ($count == 1000) { // Enviar lotes de 1000 en 1000 para que puedan ser encolados correctamente
                $this->mail($emailParams);

                $emailParams['to'] = array();
                $emailParams['substitutions']['%ROUTE%'] = array();
        
                $count = 0; // reiniciar el contador
            }
        }

        // enviar los correos restantes
        $this->mail($emailParams);

        $this->redirect('email_thankyou');
    }

    public function executeSendPrizeRemainderMail(sfWebRequest $request) {
        $promo = $this->getRoute()->getObject();
        $affiliate = $promo->getAffiliate()->getName();

        $query = $this->getPrizeRemainderQuery($promo->getId());

        $result = $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY_SHALLOW)->execute();
        
        if (!count($result)) {
            $this->redirect('email_noprizes');
        }
        
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_ep_domain','lealtag.com'),
                ));
        
        $emailParams = array(
            'subject'       => str_replace('%affiliate%', $affiliate, sfConfig::get('app_emails_prize_remainder_subject')),
            'teaser'        => sfConfig::get('app_emails_prize_remainder_teaser'),
            'to'            => array(),
            'html'          => 'email/emailPrizeRemainder',
            'sections'      => array('%AFFILIATENAME%' => iconv("ISO-8859-1", "WINDOWS-1252", $affiliate),),
            'substitutions' => array('%ROUTE%' => array(), '%FULLNAME%' => array(), '%AFFILIATE%' => array()),
        );

        $count = 0;
        
        foreach ($result as $row) {
            $emailParams['to'][] = $row['email'];
            $emailParams['substitutions']['%ROUTE%'][] = $routing->generate('user_prize', array('alpha_id' => $row['alpha_id']), true);
            $emailParams['substitutions']['%FULLNAME%'][] = iconv("ISO-8859-1", "WINDOWS-1252", $row['fullname']);
            $emailParams['substitutions']['%AFFILIATE%'][] = '%AFFILIATENAME%';
            
            $count++;
            
            if ($count == 1000) { // Enviar lotes de 1000 en 1000 para que puedan ser encolados correctamente
                $this->mail($emailParams);
                
                $emailParams['to'] = array();
                $emailParams['substitutions']['%ROUTE%'] = array();
                $emailParams['substitutions']['%FULLNAME%'] = array();
                $emailParams['substitutions']['%AFFILIATE%'] = array();
                
                $count = 0; // reiniciar el contador
            }
        }
        
        // enviar los correos restantes
        $this->mail($emailParams);

        $this->redirect('email_thankyou');
    }

    public function executeSendMail(sfWebRequest $request) {
        $param = $request->getParameter('query');
        $promo_id = $request->getParameter('promo');
        $email_list = $request->getParameter('email_list');
        $subject = $html = $parameters = '';


        if ($param == '1') {
            $query = Doctrine_Query::create()
                    ->from('sfGuardUser u')
                    ->leftJoin('u.UserProfile up')
                    ->where('u.pre_registered=1')
                    ->setHydrationMode(Doctrine::HYDRATE_ARRAY);
            $prereg = $query->execute();
            $emails = array();


            foreach ($prereg as $u) {
                $emails[] = $u['email_address'];
                $parameters = array("route1" => "http://www.lealtag.com/usuario/registrar/" . $u['UserProfile']['validate']);
            }

            $parameters = array("route1" => "");
            $subject = "Has sido premiado";
            $html = $this->getPartial('email/sendValidatePreregistered', $parameters);
        }

        if ($param == '2') {
            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $query = "SELECT DISTINCT c.user_id AS c__user_id, s.email_address FROM card c LEFT JOIN sf_guard_user s ON c.user_id = s.id LEFT JOIN promo p ON c.promo_id = p.id WHERE p.id=" . $promo_id;
            $result = $q->execute($query);
            $result = $result->fetchAll();
            foreach ($result as $u) {
                $emails[] = $u['email_address'];
            }

            $parameters = array("fullname" => "", "asset" => "", "feedback" => "");
            $subject = "Busca tu premio";
            $html = $this->getPartial('email/sendRedeemNotification', $parameters);
        }

        if ($param == '3') {
            $emails = explode(";", $email_list);
            $parameters = array("fullname" => "", "asset" => "", "feedback" => "");
            $subject = "Has sido premiado";
            $html = $this->getPartial('email/sendTagNotification', $parameters);
        }

        $message = $this->getMailer()->compose();
        $message->setSubject($subject);
        $message->setBcc($emails);
        $message->setFrom('no-reply@lealtag.com', 'Lealtag');
        $message->setBody($html, 'text/html');
        $number = $this->getMailer()->send($message);

        $a = new epSwift_DoctrineSpool();
        $a->start();
        $t = array('query' => $query, 'affiliate' => 1, 'asset' => 1, 'promo' => $promo_id, 'quantity' => $number);
        $a->setParameters($t);
        //$a->queueMessage($message);
        $a->stop();
        $this->redirect('email_thankyou');
    }

    protected function getPrizeRemainderQuery($promo) {
        $query = Doctrine_Query::create()->from('Card c')->select('c.id as card, c.alpha_id as alpha_id');

        $query->addSelect('DISTINCT u.id as user');
        $query->addSelect('up.email as email, up.fullname as fullname, up.gender as gender');

        $query->leftJoin('c.User u ON u.id = c.user_id');
        $query->leftJoin('u.UserProfile up ON up.user_id = u.id');

        $query->addWhere('c.promo_id = ?', $promo);
        $query->andWhereIn('c.status', array('complete', 'exchanged'));

        return $query;
    }

    public function executeTest(sfWebRequest $request) {
        $subject = "PROBANDO EL SUBJECT";
        $email = "jhrs21@yahoo.com";
        $user = Doctrine::getTable('sfGuardUser')->findOneByEmailAddress($email);
        $parameters = array('fullname' => $user->getUserProfile()->getFullname(), 'asset' => '', 'feedback' => '');
        $html = $this->getPartial('email/pruebaEmailTemplate', $parameters);

        $message = $this->getMailer()->compose();
        $message->setSubject($subject);
        $message->setTo($email);
        $message->setFrom('no-reply@lealtag.com', 'Lealtag');
        $message->setBody($html, 'text/html');

        $a = new epSwift_DoctrineSpool();
        $a->start();
        $a->queueMessage($message);
        $a->stop();
        echo 'Listo ' . $email;
    }

    public function executeThankyou(sfWebRequest $request) {}
    
    public function executeNoPreRegs(sfWebRequest $request) {}
    
    public function executeNoPrizes(sfWebRequest $request) {}
    
    public function executeTestMail(sfWebRequest $request) {
        $emailParams = array(
            'subject'       => 'Prueba de layout de emails',
            'to'            => array('jacobo.amn87@gmail.com','jacobo.amn87@outlook.com','jacobo.amn87@yahoo.com'),
            'html'          => 'email/emailPreregistered',
            'category'      => array('testing'),
        );
        
        $this->mail($emailParams);

        $this->redirect('email_thankyou');
    }
    
    public function executeMessageStats(sfWebRequest $request) {
        $this->emailMessage = $this->getRoute()->getObject();
        
        $this->setLayout('layout_blueprint');
    }

    protected function sendMail(MailMessage $mail, $data, $html = '') {
        $emailParams = array(
            'subject'       => $mail->getSubject(),
            'teaser'        => $mail->getTeaser(),
            'to'            => array(),
            'html'          => $html ? $html : 'email/bodies/plainCustom',
            'sections'      => array('%EMAILBODY%' => $mail->getBody(),),
            'substitutions' => array('%FULLNAME%' => array(), '%NAME%' => array(), '%LASTNAME%' => array()),
        );
        
        $count = 0;
        
        foreach ($data as $row) {
            $emailParams['to'][] = $row['email'];
            $emailParams['substitutions']['%FULLNAME%'][] = iconv("ISO-8859-1", "WINDOWS-1252", $row['fullname']);
            $emailParams['substitutions']['%NAME%'][] = iconv("ISO-8859-1", "WINDOWS-1252", $row['name']);
            $emailParams['substitutions']['%LASTNAME%'][] = iconv("ISO-8859-1", "WINDOWS-1252", $row['lastname']);
            $emailParams['substitutions']['%BODYCONTENT%'][] = '%EMAILBODY%';
            
            $count++;
            
            if ($count == 1000) { // Enviar lotes de 1000 en 1000 para que puedan ser encolados correctamente
                $this->mail($emailParams);
                
                $emailParams['to'] = array();
                $emailParams['substitutions']['%FULLNAME%'] = array();
                $emailParams['substitutions']['%NAME%'] = array();
                $emailParams['substitutions']['%LASTNAME%'] = array();
                
                $count = 0; // reiniciar el contador
            }
        }
        
        // enviar los correos restantes
        $this->mail($emailParams);

        $this->redirect('email_message_stats',$mail);
    }

    protected function mail($options) {
        $required = array('subject', 'to', 'html');

        foreach ($required as $option) {
            if (!isset($options[$option])) {
                throw new sfException("Required option $option not supplied to emailActions::mail");
            }
        }

        $sendgrid = new SendGrid(sfConfig::get('app_sendgrid_username'), sfConfig::get('app_sendgrid_password'));

        $mail = new SendGrid\Mail();
        
        $layout = $this->getPartial('email/layout');
        
        $body = $this->getPartial($options['html']);
        
        $teaser = !empty($options['teaser']) ? $options['teaser'] : '';

        $mail->setFrom(sfConfig::get('app_sendgrid_email'))->
                setFromName(sfConfig::get('app_sendgrid_name'))->
                setSubject($options['subject'])->
                setHtml(str_replace(array('%EMAIL_TEASER%','%EMAIL_BODY%'), array($teaser,$body), $layout));
        
        if (!empty($options['text'])) {
            $text = $this->getPartial('email/layoutText', array('teaser' => $teaser, 'body' => $this->getPartial($options['text'])));
            
            $mail->setText($text);
        }

        if (is_array($options['to'])) {
            if (count($options['to']) > 1000) {
                throw new sfException("the maximun number of recipients is 1000 - emailActions::mail");
            }
            
            $mail->setTos($options['to']);
        } else {
            $mail->setTo($options['to']);
        }
        
        if (isset($options['substitutions'])) {
            foreach ($options['substitutions'] as $tag => $values) {
                $mail->addSubstitution($tag, $values);
            }
        }
        
        if (isset($options['sections'])) {
            foreach ($options['sections'] as $tag => $value) {
                $mail->addSection($tag, $value);
            }
        }
        

        if (isset($options['category'])) {
            if (is_array($options['category'])) {
                if (count($options['category']) > 10) {
                    throw new sfException("the maximun number of categories that can be set is 10 - emailActions::mail");
                }

                foreach ($options['category'] as $category) {
                    $mail->addCategory($category);
                }
            } else {
                $mail->addCategory($options['category']);
            }
        }

        $sendgrid->smtp->send($mail);
    }
}
