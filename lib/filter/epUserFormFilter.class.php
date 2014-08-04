<?php

/**
 * epUserFormFilter filter form.
 *
 * @package    elperro
 * @subpackage filter
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class epUserFormFilter extends sfGuardUserFormFilter {

    public function configure() {
        parent::configure();
        
        $this->setOption('query', $this->getBaseQuery());
        
        $this->useFields(array('is_active','pre_registered'));
        
        $this->widgetSchema['affiliate_id'] = new sfWidgetFormDoctrineChoice(
                array(
                    'model' => $this->getRelatedModelName('Affiliate'),
                    'expanded' => true,
                    'multiple' => true,
                    'label' => false,
                )
            );

        $this->validatorSchema['affiliate_id'] = new sfValidatorDoctrineChoice(
                array(
                    'model' => $this->getRelatedModelName('Affiliate'),
                    'multiple' => true,
                    'required' => false
                ));
        
        $this->widgetSchema['asset_id'] = new sfWidgetFormDoctrineChoice(
                array(
                    'model' => $this->getRelatedModelName('Asset'),
                    'expanded' => true,
                    'multiple' => true,
                    'label' => false,
                )
            );

        $this->validatorSchema['asset_id'] = new sfValidatorDoctrineChoice(
                array(
                    'model' => $this->getRelatedModelName('Asset'),
                    'multiple' => true,
                    'required' => false
                ));
        
        $this->setWidget(
                'last_interaction',
                new sfWidgetFormFilterDate(
                    array(
                        'from_date' => new sfWidgetFormInputText(
                                array(),
                                array('class' => 'li_from_date uses-datepicker', 'data-bvalidator' => 'date[dd/mm/yyyy]', 'data-bvalidator-msg' => 'Ingresa una fecha en formato dd/mm/aaaa')
                            ), 
                        'to_date' => new sfWidgetFormInputText(
                                array(),
                                array('class' => 'li_to_date uses-datepicker', 'data-bvalidator' => 'date[dd/mm/yyyy]', 'data-bvalidator-msg' => 'Ingresa una fecha en formato dd/mm/aaaa')
                            ),
                        'template' => '<div class="filter_range_label">Desde</div><div class="filter_range_input">%from_date%</div><div class="filter_range_label">Hasta</div><div class="filter_range_input">%to_date%</div>',
                        'with_empty' => false,
                        'label' => false
                    )
                )
            );
        
        $this->setValidator(
                'last_interaction', 
                new sfValidatorDateRange(array(
                        'required' => false, 
                        'from_date' => new sfValidatorDateTime(array('required' => false, 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'datetime_output' => 'Y-m-d 00:00:00')), 
                        'to_date' => new sfValidatorDateTime(array('required' => false, 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'datetime_output' => 'Y-m-d 23:59:59'))
                    )
                )
            );
        
        $this->setWidget(
                'created_at',
                new sfWidgetFormFilterDate(
                    array(
                        'from_date' => new sfWidgetFormInputText(
                                array(),
                                array('class' => 'from_date uses-datepicker', 'data-bvalidator' => 'date[dd/mm/yyyy]', 'data-bvalidator-msg' => 'Ingresa una fecha en formato dd/mm/aaaa')
                            ), 
                        'to_date' => new sfWidgetFormInputText(
                                array(),
                                array('class' => 'to_date uses-datepicker', 'data-bvalidator' => 'date[dd/mm/yyyy]', 'data-bvalidator-msg' => 'Ingresa una fecha en formato dd/mm/aaaa')
                            ),
                        'template' => '<div class="filter_range_label">Desde</div><div class="filter_range_input">%from_date%</div><div class="filter_range_label">Hasta</div><div class="filter_range_input">%to_date%</div>',
                        'with_empty' => false,
                        'label' => false
                    )
                )
            );
        
        $this->setValidator(
                'created_at', 
                new sfValidatorDateRange(array(
                        'required' => false, 
                        'from_date' => new sfValidatorDateTime(array('required' => false, 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'datetime_output' => 'Y-m-d 00:00:00')), 
                        'to_date' => new sfValidatorDateTime(array('required' => false, 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'datetime_output' => 'Y-m-d 23:59:59'))
                    )
                )
            );

        $this->setWidget('age_range', new sfWidgetFormFilterInputRange(array(
                    'from_value' => new sfWidgetFormInput(array(),array('class' => 'from_age', 'data-bvalidator' => 'digit', 'data-bvalidator-msg' => 'Ingrese sólo números')),
                    'to_value' => new sfWidgetFormInput(array(),array('class' => 'from_age', 'data-bvalidator' => 'digit', 'data-bvalidator-msg' => 'Ingrese sólo números')),
                    'label' => false,
                    'template' => '<div class="filter_range_label">Desde</div><div class="filter_range_input">%from_value%</div><div class="filter_range_label">Hasta</div><div class="filter_range_input">%to_value%</div>',
                    'with_empty' => false
                )));

        $this->setValidator('age_range', new sfValidatorInputRange(array(
                    'required' => false,
                    'from_value' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
                    'to_value' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false)))
                )));

        $genderOptions = array('male' => 'Masculino', 'female' => 'Femenino');

        $this->setWidget('gender', new sfWidgetFormChoice(array(
                    'choices' => $genderOptions,
                    'expanded' => true,
                    'multiple' => true,
                    'label' => false,
                )));

        $this->setValidator('gender', new sfValidatorChoice(array(
                    'choices' => array_keys($genderOptions),
                    'required' => false,
                    'multiple' => true,
                )));
        
        $this->widgetSchema->setLabels(array(
            'is_active'         => 'Esta Activo',
            'pre_registered'    => 'Esta Pre-Registrado',
            'affiliate_id'      => 'Afiliados',
            'asset_id'          => 'Establecimientos',
            'gender'            => 'Generos',
            'age_range'         => 'Edades',
            'created_at'        => 'Fecha de Registro',
            'last_interaction'  => 'Fecha de Última Interacción',
        ));
        
        //$this->widgetSchema->setFormFormatterName('epFilter');
    }

    public function addAffiliateIdColumnQuery(Doctrine_Query $query, $field, $value) {        
        if (!empty($value)) {
            $query->andWhereIn('s.affiliate_id', $value);
        }
        
        return $query;
    }
    
    public function addAssetIdColumnQuery(Doctrine_Query $query, $field, $value) {        
        if (!empty($value)) {
            $query->andWhereIn('s.asset_id', $value);
        }
        
        return $query;
    }
    
    public function addLastInteractionColumnQuery(Doctrine_Query $query, $field, $value) {        
        if (isset($value['from']) && $value['from']) {
            $query->andWhere('s.last_interaction >= ?', $value['from']);
        }
        if (isset($value['to']) && $value['to']) {
            $query->andWhere('s.last_interaction <= ?', $value['to']);
        }
        
        return $query;
    }
    
    public function addAgeRangeColumnQuery(Doctrine_Query $query, $field, $value) {        
        if (isset($value['from']) && $value['from']) {
            $query->addHaving('age >= ?', $value['from']);
        }
        if (isset($value['to']) && $value['to']) {
            $query->addHaving('age <= ?', $value['to']);
        }
        
        return $query;
    }
    
    public function addGenderColumnQuery(Doctrine_Query $query, $field, $values) {
        $having = '';
        $count = count($values);
        $i = 0;
        
        foreach ($values as $key => $value) {
            $having .= 'gender = ?';
            $i++;
            if ($i < $count) {
                $having .= ' OR ';
            }
        }
        
        if ($having) {
            $query->addHaving($having, $values);
        }
        
        return $query;
    }
    
    protected function getBaseQuery() {        
        $query = Doctrine_Query::create()->from('sfGuardUser u')->select(
                'u.id as user, u.first_name as name, u.last_name as lastname,u.email_address as email, u.created_at, u.is_active, u.pre_registered'
            );
        
        $query->addSelect('up.id as user_profile');
        $query->addSelect('up.fullname as fullname');
        $query->addSelect('FLOOR(DATEDIFF(NOW(),up.birthdate)/365.2425) as age');
        $query->addSelect('up.gender as gender');
        
        $query->leftJoin('u.UserProfile up');
        $query->leftJoin('u.Subscriptions s');
        
        $query->groupBy('user');
        
        return $query;
    }
}
