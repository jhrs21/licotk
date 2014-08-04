<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of epUserApplyWebmobileForm
 *
 * @author usurio
 */
class epUserApplyWebmobileForm extends epUserApplyForm {

    public function configure() {
        parent::configure();

        unset($this['password2'], $this['phone']);
        
        $this->setWidget('password', new sfWidgetFormInput(
                        array('label' => 'Contraseña:'),
                        array('maxlength' => 128, 'data-bvalidator' => 'minlength[6],required', 'data-bvalidator-msg' => 'Ingrese una contraseña de 6 caracteres mínimo.')
        ));
        
        $years = range(date("Y")-6,date("Y")-90);

        $this->setWidget('birthdate', new sfWidgetFormDate(
                        array('label' => 'Fecha de nacimiento:',
                              'format' => '%day% / %month% / %year%', 
                              'years' => array_combine($years, $years))
        ));
        $this->setValidator('birthdate', new sfValidatorDate(
                    array('date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~'),
                    array('required' => 'Campo obligatorio.', 'invalid' => 'Fecha inválida.', 'bad_format' => 'La fecha "%value%" no esta en el formato dd/mm/aaaa.')
                ));
    }
    
    public function getPostValidators() {
        $validators = array(
                new sfValidatorDoctrineUnique(array('model' => 'UserProfile', 'column' => 'id_number'), array('invalid' => 'El número de cedula indicado ya se encuentra vinculado a una cuenta.')),
            );

        if ($this->getObject()->isNew()) {
            $validators[] = new epValidatorUserEmailUnique(array(), array('invalid' => 'Ya ha sido registrada una cuenta con este correo electrónico.<br>Para ingresar haz clic <a href="' . sfContext::getInstance()->getRouting()->generate('sf_guard_signin') . '">Aquí</a>.'));
        }

        return $validators;
    }

}

?>
