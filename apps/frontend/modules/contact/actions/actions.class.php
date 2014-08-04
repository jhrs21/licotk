<?php

/**
 * contact actions.
 *
 * @package    elperro
 * @subpackage contact
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class contactActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->form = new epContactForm();

        if ($request->isMethod('post')) {
            
            $captcha = array(
                'recaptcha_challenge_field' => $request->getParameter('recaptcha_challenge_field'),
                'recaptcha_response_field' => $request->getParameter('recaptcha_response_field'),
            );
            
            $this->form->bind(array_merge($request->getParameter($this->form->getName()), array('captcha' => $captcha)));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $message = $this->getMailer()->compose(
                        array('no-reply@lealtag.com'), 'info@lealtag.com', 'Empresa interesada via formulario web', <<<EOF
                                Nombre de la empresa: {$values['business']}
                                Direccion: {$values['address']}
                                Rif: {$values['rif']}
                                Tipo de empresa: {$values['type']}
                                Nombre de contacto: {$values['name']}
                                Teléfono: {$values['phone']}
                                Correo: {$values['email']}

                                Formulario de contacto.
EOF
                );

                $this->getMailer()->send($message);
                $this->getUser()->setFlash('send_email_succeeded', '¡Hemos registrado tus datos! Pronto nos pondremos en contacto');
                $this->redirect('contact/index');
            }
        }
    }

    protected function mail($options) {
        $message = $this->getMailer()->compose(
                array('no-reply@lealtag.com'), 'ventas@lealtag.com', 'Empresa interesada via formulario web', <<<EOF
Nombre de la empresa: {$options['business']}
Direccion: {$options['address']}
Rif: {$options['rif']}
Tipo de empresa: {$options['type']}
Nombre de contacto: {$options['name']}
Teléfono: {$options['phone']}
Correo: {$options['email']}
 
Formulario de contacto.
EOF
        );

        $this->getMailer()->send($message);
    }

    public function executeThankyou(sfWebRequest $request) {
        var_dump('hola', $request->getPostParameters());
    }

    public function executeQuestionAndSuggestion(sfWebRequest $request) {
        $this->form = new epQASForm();

        if ($request->isMethod('post')) {
            $captcha = array(
                'recaptcha_challenge_field' => $request->getParameter('recaptcha_challenge_field'),
                'recaptcha_response_field' => $request->getParameter('recaptcha_response_field'),
            );
            
            $this->form->bind(array_merge($request->getParameter($this->form->getName()), array('captcha' => $captcha)));
            
            //$this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $this->sendQASMail($values);
                $this->redirect('contacto_agradecimiento');
            }
        }
    }

    protected function sendQASMail($params) {
        $this->sendMail(array(
            'subject' => 'Preguntas o sugerencias de usuario',
            'fullname' => 'LealTag',
            'email' => 'info@lealtag.com',
            'parameters' => array(
                'name' => $params['name'],
                'email' => $params['email'],
                'message' => $params['message']
            ),
            'text' => 'contact/sendQASText',
            'html' => 'contact/sendQAS'
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
