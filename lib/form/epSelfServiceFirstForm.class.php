<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of epSelfServiceFirstForm
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epSelfServiceFirstForm extends BaseForm {

    public static function getCategories() {
        $result = array();
        $categories = Doctrine::getTable('Category')->findBy('category_type','place',Doctrine_Core::HYDRATE_ARRAY);
        foreach ($categories as $category) {
            $result[$category['name']] = $category['name'];
        }
        return $result;
    }

    public function configure() {
        $this->setWidgets(array(
            'affiliate' => new sfWidgetFormInputText(),
            'email' => new sfWidgetFormInputText(),
            'username' => new sfWidgetFormInputText(),
            'password' => new sfWidgetFormInputPassword(),
            'category' => new sfWidgetFormChoice(array('choices' => self::getCategories()))
        ));

        $this->widgetSchema->setLabels(array(
            'affiliate' => 'Empresa',
            'email' => 'Correo electrónico',
            'username' => 'Usuario',
            'password' => 'Contraseña',
            'category' => 'Categoría'
        ));

        $this->setValidators(array(
            'affiliate' => new sfValidatorString(),
            'email' => new sfValidatorEmail(),
            'username' => new sfValidatorString(),
            'password' => new sfValidatorString(),
            'category' => new sfValidatorChoice(array('choices' => array_keys(self::getCategories())))
        ));

        $this->widgetSchema->setFormFormatterName('epWeb');
        $this->widgetSchema->setNameFormat('firstStep[%s]');
    }

}

?>
