<?php

/**
 * email actions.
 *
 * @package    elperro
 * @subpackage email
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class emailActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        try {
            $profile = $this->form->getObject();
            $this->sendVerificationMail($profile);
            return 'After';
        } catch (Exception $e) {
            $mailer->disconnect();
            $profile = $this->form->getObject();
            $user = $profile->getUser();
            $user->delete();
            // You could re-throw $e here if you want to 
            // make it available for debugging purposes
            return 'MailerError';
        }
    }

    protected function mail($options, $sendImmediately = false) {
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

        if ($sendImmediately) {
            $this->getMailer()->sendNextImmediately()->send($message);
        } else {
            $this->getMailer()->send($message);
        }
    }

    protected function sendEmail($profile) {
        $route = $routing->generate('validate', array('validate' => $profile->getValidate()), true);
        $this->mail(array(
            'subject' => sfConfig::get(
                    'app_sfApplyPlugin_apply_subject', sfContext::getInstance()->
                            getI18N()->__(
                            "Please verify your account on %1%", array('%1%' => $this->getRequest()->getHost())
                    )
            ),
            'fullname' => $profile->getFullname(),
            'email' => $profile->getEmail(),
            'parameters' => array(
                'fullname' => $profile->getFullname(),
                'route1' => $route,
                'name' => $profile->getUser()->getFirstName(),
                'gender' => $profile->getGender()
            ),
            'text' => 'email/sendValidateNewText',
            'html' => 'email/sendValidateNew'
        ));
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
