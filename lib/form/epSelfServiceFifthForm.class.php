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
class epSelfServiceFifthForm extends BaseForm {

    public function configure() {
        $this->setWidgets(array(
            'term1' => new sfWidgetFormTextarea(array(), array('cols' => 100, 'rows' => 3)),
            'term2' => new sfWidgetFormTextarea(array(), array('cols' => 100, 'rows' => 3)),
            'term3' => new sfWidgetFormTextarea(array(), array('cols' => 100, 'rows' => 3)),
            'term4' => new sfWidgetFormTextarea(array(), array('cols' => 100, 'rows' => 3)),
            'term5' => new sfWidgetFormTextarea(array(), array('cols' => 100, 'rows' => 3)),
            'term6' => new sfWidgetFormTextarea(array(), array('cols' => 100, 'rows' => 3)),
            'term7' => new sfWidgetFormTextarea(array(), array('cols' => 100, 'rows' => 3)),
            'term8' => new sfWidgetFormTextarea(array(), array('cols' => 100, 'rows' => 3)),
            'term9' => new sfWidgetFormTextarea(array(), array('cols' => 100, 'rows' => 3)),
        ));

        $this->widgetSchema->setLabels(array(
            'term1' => 'Condición',
            'term2' => 'Condición',
            'term3' => 'Condición',
            'term4' => 'Condición',
            'term5' => 'Condición',
            'term6' => 'Condición',
            'term7' => 'Condición',
            'term8' => 'Condición',
            'term9' => 'Condición',
        ));

        $this->setValidators(array(
            'term1' => new sfValidatorString(),
            'term2' => new sfValidatorString(),
            'term3' => new sfValidatorString(),
            'term4' => new sfValidatorString(),
            'term5' => new sfValidatorString(),
            'term6' => new sfValidatorString(),
            'term7' => new sfValidatorString(),
            'term8' => new sfValidatorString(),
            'term9' => new sfValidatorString(),
        ));

        $this->setDefaults(array(
            'term1' => 'Se aceptará el canje de un (1) cupón por factura, por cliente por día. De tener más de un (1) cupón deberá ser canjeado otro día distinto.',
            'term2' => 'El cupón deberá ser enseñado desde la aplicación Lealtag o de forma impresa al momento de la facturación para su canje.',
            'term3' => 'No se devuelve dinero a cambio del cupón.',
            'term4' => 'Ningún empleado o familiar de los mismos podrán participar en la promoción.',
            'term5' => 'El cupón NO podrá ser utilizado en conjunto con otra promoción.',
            'term6' => 'Cada cupón es válido para una (1) sola compra, no es reusable.',
            'term7' => 'Máximo de cupones canjeables por factura uno (1).',
            'term8' => 'No se aceptará combinaciones de cupones en una misma factura.',
            'term9' => 'No se permitirá dividir montos en varias facturas para obtener más cupones.',
        ));

        $this->widgetSchema->setFormFormatterName('epWeb');
        $this->widgetSchema->setNameFormat('fifthStep[%s]');
    }

}

//Los Tags requeridos para reclamar un premio podrán ser acumulados desde el 19/03/2012 hasta el 16/06/2012
//Los premios podrán ser canjeados desde el 19/03/2012 hasta el 25/03/2012
?>