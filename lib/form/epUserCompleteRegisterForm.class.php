<?php

class epUserCompleteRegisterForm extends UserProfileForm {

    protected $validate = null;
    protected $userId = null;
    protected $profileId = null;
    protected $userHash = null;
    protected $userFullname = null;

    public function configure() {
        parent::configure();

        unset(
            $this['user_id'], $this['validate'], $this['fullname'], $this['id_number'], 
            $this['country_id'], $this['state_id'], $this['municipality_id'], $this['city_id']
        );

        $this->setWidget('first_name', new sfWidgetFormInput(
                array(),
                array(
                    'maxlength' => 100,
                    'data-bvalidator' => 'validateregex,required',
                    'data-bvalidator-msg' => 'Campo obligatorio. Ingrese sólo letras.'
                )
            ));

        $this->widgetSchema->moveField('first_name', sfWidgetFormSchema::FIRST);

        $this->setWidget('last_name', new sfWidgetFormInput(
                array(),
                array(
                    'maxlength' => 100,
                    'data-bvalidator' => 'validateregex,required',
                    'data-bvalidator-msg' => 'Campo obligatorio. Ingrese sólo letras.'
                )
            ));

        $this->widgetSchema->moveField('last_name', sfWidgetFormSchema::AFTER, 'first_name');

        $this->setWidget('birthdate', new sfWidgetFormInputText(
                array(),
                array(
                    'class' => 'uses-datepicker',
                    'data-bvalidator' => 'date[dd/mm/yyyy],required',
                    'data-bvalidator-msg' => 'Ingrese una fecha en formato dd/mm/aaaa'
                )
            ));

        $this->widgetSchema->moveField('birthdate', sfWidgetFormSchema::AFTER, 'last_name');

        $this->setWidget('gender', new sfWidgetFormChoice(
                array('choices' => array('' => '', 'female' => 'Femenino', 'male' => 'Masculino')),
                array(
                    'data-bvalidator' => 'required',
                    'data-bvalidator-msg' => 'Campo obligatorio.'
                )
            ));

        $this->widgetSchema->moveField('gender', sfWidgetFormSchema::AFTER, 'birthdate');

        $this->setWidget('id_number', new sfWidgetFormInput(
                array(),
                array(
                    'maxlength' => 8,
                    'data-bvalidator' => 'digit,required',
                    'data-bvalidator-msg' => 'Ingrese sólo números.'
                )
            ));

        $this->widgetSchema->moveField('id_number', sfWidgetFormSchema::AFTER, 'gender');

        $this->setWidget('phone', new sfWidgetFormPhoneNumber(
                array('format' => '%code%-%number%'),
                array(
                    'data-bvalidator' => 'digit',
                    'data-bvalidator-msg' => 'Ingrese sólo números.'
                )
            ));

        $this->widgetSchema->moveField('phone', sfWidgetFormSchema::AFTER, 'id_number');
        
        $this->widgetSchema->setLabels(array(
                'first_name' => 'Nombre(s):',
                'last_name' => 'Apellido(s):',
                'id_number' => 'Cédula de Identidad:',
                'phone' => 'Celular:',
                'gender' => 'Género:',
                'birthdate' => 'Fecha de Nacimiento:',
            ));

        $this->setValidator('first_name', new sfValidatorAnd(
                array(
                    new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 100)),
                    new sfValidatorRegex(
                            array('pattern' => '/^[^<>&\|\$]+$/'),
                            array('invalid' => 'Un nombre no puede contener &lt;, &gt;, $, | o &amp;.')
                        )
                ),
                array(),
                array('required' => 'Campo obligatorio.')
            ));

        $this->setValidator('last_name', new sfValidatorAnd(
                    array(
                        new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 100)),
                        new sfValidatorRegex(
                            array('pattern' => '/^[^<>&\|\$]+$/'),
                            array('invalid' => 'Un apellido no puede contener &lt;, &gt;, $, | o &amp;.')
                        )
                    ),
                    array(),
                    array('required' => 'Campo obligatorio.')
                )
            );

        $this->setValidator('id_number', new sfValidatorAnd(
                    array(
                        new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 8)),
                        new sfValidatorRegex(
                            array('pattern' => '/^[[:digit:]]+$/'),
                            array('invalid' => 'Solo puede contener dígitos sin puntos ni comas')
                        )
                    ),
                    array('required' => false),
                    array('required' => 'Campo obligatorio.')
                )
            );

        $this->setValidator('phone', new sfValidatorAnd(
                    array(
                        new sfValidatorPhoneNumber(
                                array('required' => true),
                                array('invalid' => 'Número telefónico inválido.')
                        )
                    ),
                    array('required' => false),
                    array('required' => 'Campo obligatorio.')
                )
            );

        $this->validatorSchema['gender']->setMessage('required', 'Campo obligatorio.');

        $this->setValidator('birthdate', new sfValidatorDate(
                array('date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~'),
                array(
                    'required' => 'Campo obligatorio.',
                    'invalid' => 'Fecha inválida.',
                    'bad_format' => 'La fecha "%value%" no esta en el formato dd/mm/aaaa.'
                )
            ));

        $emailWidget = $this->getWidget('email');

        $emailWidget->setOption('label', 'Correo Electrónico:');

        $emailWidget->setAttributes(array(
                'data-bvalidator' => 'email, required',
                'data-bvalidator-msg' => 'Dirección de correo electrónico inválida.',
                'readonly' => 'readonly'
            ));
        
        $emailValidator = $this->getValidator('email');
        
        $emailValidator->setOption('required', true);

        $this->setWidget('password', new sfWidgetFormInputPassword(
                array('label' => 'Contraseña:'),
                array(
                    'maxlength' => 128,
                    'data-bvalidator' => 'minlength[6],required',
                    'data-bvalidator-msg' => 'Ingrese una contraseña de 6 caracteres mínimo.'
                )
            ));

        $this->widgetSchema->moveField('password', sfWidgetFormSchema::AFTER, 'email');

        $this->setWidget('password2', new sfWidgetFormInputPassword(
                array('label' => 'Confirmar Contraseña:'),
                array(
                    'maxlength' => 128,
                    'data-bvalidator' => 'equalto[sfApplyApply_password], required',
                    'data-bvalidator-msg' => 'Las contraseñas no coinciden.'
                )
            ));

        $this->widgetSchema->moveField('password2', sfWidgetFormSchema::AFTER, 'password');

        $this->setValidator('email', new sfValidatorAnd(
                array(
                    new sfValidatorEmail(
                            array('required' => true, 'trim' => true),
                            array('invalid' => 'Correo electrónico inválido.')
                    ),
                    new sfValidatorString(array('required' => true, 'max_length' => 80))
                ),
                array(),
                array('required' => 'Campo obligatorio.')
            ));

        $this->setValidator('password', new sfValidatorString(
                array('required' => true, 'trim' => true, 'min_length' => 6, 'max_length' => 128),
                array(
                    'required' => 'Campo obligatorio.',
                    'min_length' => 'La contraseña es muy corta. Debe contener entre %min_length% y %max_length% caracteres.',
                    'max_length' => 'La contraseña es muy corta. Debe contener entre %min_length% y %max_length% caracteres.'
                )
            ));

        $this->setValidator('password2', new sfValidatorString(
                array('required' => true, 'trim' => true, 'min_length' => 6, 'max_length' => 128),
                array(
                    'required' => 'Campo obligatorio.',
                    'min_length' => 'La contraseña es muy corta. Debe contener entre %min_length% y %max_length% caracteres.',
                    'max_length' => 'La contraseña es muy corta. Debe contener entre %min_length% y %max_length% caracteres.'
                )
            ));

        $this->setWidget('privacyPolicy', new sfWidgetFormInputCheckbox());

        $this->setDefault("privacyPolicy", false);

        $this->widgetSchema->setLabel('privacyPolicy', 'Acepto los términos de privacidad');

        $this->setValidator('privacyPolicy', new sfValidatorBoolean(array('required' => true), array('required' => 'Debe aceptar los términos de privacidad.')));

        $this->widgetSchema->setNameFormat('sfApplyApply[%s]');

        $schema = $this->validatorSchema;

        $postValidator = $schema->getPostValidator();

        $postValidators = $this->getPostValidators();

        if ($postValidator) {
            $postValidators[] = $postValidator;
        }

        $this->validatorSchema->setPostValidator(new sfValidatorAnd($postValidators));
    }

    public function getPostValidators() {
        $validators = array(
            new sfValidatorDoctrineUnique(
                    array('model' => 'UserProfile', 'column' => 'id_number'),
                    array('invalid' => 'El número de cedula indicado ya se encuentra vinculado a una cuenta.')
            ),
            new epValidatorUserEmailUnique(
                    array(),
                    array('invalid' => 'Ya ha sido registrada una cuenta con este correo electrónico.<br>Para ingresar haz clic <a href="' . sfContext::getInstance()->getRouting()->generate('sf_guard_signin') . '">Aquí</a>.')
            ),
            new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'password2',
                    array(),
                    array('invalid' => 'Las contraseñas no coinciden.')
            ),
        );

        return $validators;
    }

    public function setUserHash($hash) {
        $this->userHash = $hash;
    }

    public function doSave($con = null) {
        $user = $this->getObject()->getUser();
        
        $user->setPreRegistered(false);
        $user->setIsActive(true); 
        $user->setPassword($this->getValue('password'));
        $user->setHash($this->userHash);
        $user->setFirstName($this->getValue('first_name'));
        $user->setLastName($this->getValue('last_name'));

        $user->save();

        // Getting the data needed for the profile object
        $this->userFullname = $this->getValue('first_name') . ' ' . $this->getValue('last_name');

        return parent::doSave($con);
    }

    public function updateObject($values = null) {
        $object = parent::updateObject($values);
        $object->setFullname($this->userFullname);

        // Don't break subclasses!
        return $object;
    }
}