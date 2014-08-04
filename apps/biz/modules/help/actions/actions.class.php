<?php

/**
 * help actions.
 *
 * @package    elperro
 * @subpackage help
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class helpActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->form = new epQASForm();
        $msg = 'Gracias, su pregunta ha sido enviada satisfactoriamente';
        $asset = $this->getUser()->getGuardUser()->getAsset()->getName();
        
        if ($request->isMethod('post')) {
            $captcha = array(
                'recaptcha_challenge_field' => $request->getParameter('recaptcha_challenge_field'),
                'recaptcha_response_field' => $request->getParameter('recaptcha_response_field'),
            );

            
            $this->form->bind(array_merge($request->getParameter($this->form->getName()), array('captcha' => $captcha)));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $values['asset'] = $asset;
                $this->sendHelpMail($values);
                $this->getUser()->setFlash('email_success', $msg);
            }
        }
    }

    protected function sendHelpMail($params) {
        $this->sendMail(array(
            'subject' => 'Preguntas o sugerencias de un negocio',
            'fullname' => 'LealTag',
            'email' => 'ayuda@lealtag.com',
            'parameters' => array(
                'name' => $params['name'],
                'email' => $params['email'],
                'message' => $params['message'],
                'asset' => $params['asset']
            ),
            'text' => 'helpBizMailText',
            'html' => 'helpBizMail'
        ));
    }

    protected function sendMail($options) {
        $required = array('subject', 'parameters', 'email', 'fullname', 'html', 'text');

        foreach ($required as $option) {
            if (!isset($options[$option])) {
                throw new sfException("Required option $option not supplied to sfApply::mail");
            }
        }
        $message = $this->getMailer()->compose();
        $message->setSubject($options['subject']);

        // Render message parts
        $message->setBody($this->getPartial($options['html'], $options['parameters']), 'text/html');
        $message->addPart($this->getPartial($options['text'], $options['parameters']), 'text/plain');
        $address = $this->getFromAddress();
        $message->setFrom(array($address['email'] => $address['fullname']));
        $message->setTo(array($options['email'] => $options['fullname']));
        $this->getMailer()->send($message);
    }

    protected function getFromAddress() {
        $from = sfConfig::get('app_sfApplyPlugin_from', false);
        if (!$from) {
            throw new Exception('app_sfApplyPlugin_from is not set');
        }
        // i18n the full name
        return array('email' => $from['email'], 'fullname' => sfContext::getInstance()->getI18N()->__($from['fullname']));
    }

}
