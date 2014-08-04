<?php

/**
 * Description of epBaseApiActions
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epBaseApiActions extends sfActions
{
    protected function validateApiRules($apikey, $name)
    {
        if(!self::checkApikey($apikey,$name)){
            $this->result['error'] = array(
                'message' => 'Error de autenticación de usuario del API.',
                'type' => 'ApiError',
                'code' => 'api000'
            );
            return false;
        }
        
        return true;
    }

    static protected function checkApikey($apikey, $name)
    {
        if(!$apiUser = Doctrine::getTable('ApiUser')->findOneByName($name)){
            return false;
        }
        
        $key = sha1($apiUser->getSalt().$apikey);
        
        return strcmp($apiUser->getApikey(), $key) == 0;
        
    }
    
    /**
     * Verifica si la petición contiene los parametros requeridos para ser procesada.
     * Si todos los parametros necesarios están presentes retorna verdadero, en caso
     * contrario retorna falso.
     * 
     * En la variable <b>$parameters</b> se debe pasar un arreglo con los nombres
     * de los parametros considerados obligatorios, por ejemplo:
     * 
     * $parameters = array('arg1', 'arg2', 'arg3')
     * 
     * En el caso que la ausencia de un parametro condicione como obligatoria
     * la presencia de otro(s) esto se debe representar colocando en un arreglo de 2
     * posiciones los parametros dependientes de la siguiente forma:
     * 
     * <b>a)</b> $parameters = array('arg1', 'arg2', array('main' => array('arg3'), 'fallback' => array('arg4')))
     * 
     * <b>b)</b> $parameters = array('arg1', 'arg2', array('main' => array('arg3'), 'fallback' =>array('arg4','arg5')))
     * 
     * <b>c)</b> $parameters = array('arg1', 'arg2', array('main' => array('arg3','arg4'),'fallback' => array('arg5','arg6')))
     * 
     * <b>Caso a:</b> si el parametro <i>arg3</i> no está presente en la petición, 
     * el parametro <i>arg4</i> debería estarlo.
     * 
     * <b>Caso b:</b> si el parametro <i>arg3</i> no está presente en la petición, 
     * los parametros <i>arg4</i> y <i>arg5</i> deberían estarlo.
     * 
     * <b>Caso c:</b> si alguno de los parametros <i>arg3</i> o <i>arg4</i> no está presente en la petición, 
     * los parametros <i>arg5</i> y <i>arg6</i> deberían estarlo.
     *  
     * @param array $parameters
     * 
     * @return boolean 
     */
    static protected function paramsAreOk(array $parameters, sfWebRequest $request)
    {
        foreach ($parameters as $param) {
            if(is_array($param)){
                if(!self::paramsAreOk($param['main'], $request) && !self::paramsAreOk($param['fallback'], $request)){
                    return false;
                }
            }
            else {
                if(!$request->hasParameter($param)){
                    return false;
                }
            }
        }
        
        return true;
    }
}

?>
