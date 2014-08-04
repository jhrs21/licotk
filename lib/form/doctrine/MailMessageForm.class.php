<?php

/**
 * MailMessage form.
 *
 * @package    elperro
 * @subpackage form
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MailMessageForm extends BaseMailMessageForm {

    public function configure() {
        unset($this['query_params'],$this['max_reach'],$this['created_at'],$this['updated_at']);
    }
}
