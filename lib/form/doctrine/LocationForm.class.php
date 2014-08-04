<?php

/**
 * Location form.
 *
 * @package    elperro
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class   LocationForm extends BaseLocationForm
{
    public function configure() 
    {
        unset($this['created_at'],$this['updated_at']);
        
        if($this->getOption('country', false))
        {
            $this->setDefault('country_id', $this->getOption('country'));
        }
        else if($this->object->isNew())
        {
            $this->setDefault('country_id', Doctrine::getTable('Country')->findOneByName('Venezuela')->getId());
        }
        else
        {
            $this->setDefault('country_id', $this->object->getCountryId());
        }
        
        $statesQuery = Doctrine::getTable('State')->addByCountryQuery($this->getDefault('country_id'));
        
        $this->widgetSchema['state_id'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => $this->getRelatedModelName('State'),
                    'query' => $statesQuery,
                    'add_empty' => false
                ));
        
        if($this->getOption('state', false))
        {
            $municipalitiesQuery = Doctrine::getTable('Municipality')->addByStateQuery($this->getOption('state'));
        }
        else if($this->object->isNew())
        {
            $municipalitiesQuery = Doctrine::getTable('Municipality')->addByStateQuery($statesQuery->fetchOne()->getId());
        }
        else
        {
            $municipalitiesQuery = Doctrine::getTable('Municipality')->addByStateQuery($this->object->getStateId());
        }
        
        $this->widgetSchema['municipality_id'] = new sfWidgetFormDoctrineChoice(array(
                'model' => $this->getRelatedModelName('Municipality'),
                'query' => $municipalitiesQuery,
                'add_empty' => false
            ));
        
        $this->widgetSchema->setPositions(array(
                'country_id', 'state_id', 'municipality_id', 'city_id', 'name', 'address', 'latitude', 'longitude', 'id', 'affiliate_id', 'asset_id', 
            ));
    }

}
