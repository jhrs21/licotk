<?php

require_once dirname(__FILE__) . '/../lib/validation_code_adminGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/validation_code_adminGeneratorHelper.class.php';

/**
 * validation_code_admin actions.
 *
 * @package    elperro
 * @subpackage validation_code_admin
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class validation_code_adminActions extends autoValidation_code_adminActions {

    public function executeBatchPrint(sfWebRequest $request) {
        $ids = $request->getParameter('ids');
//        $ids = array(11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25);

        $q = Doctrine_Query::create()
                ->select('vc.code, pc.serial,pc.alpha_id,vc.serial')
                ->from('ValidationCode vc')
                ->leftJoin('vc.PromoCode pc')
                ->whereIn('vc.id', $ids);

        $result = $q->execute();
        $this->setLayout(false);

        // CARACTERISTICAS DEL QR
        require(sfConfig::get('sf_lib_dir') . '/vendor/phpqrcode/qrlib.php');
        $filename = sfConfig::get('sf_data_dir') . '/qr-codes/temp.png';
        $data = "http://www.lealtag.com/";
        $errorCorrectionLevel = "L";
        $matrixPointSize = "7";

        // GENERACION DEL CODIGO QR
        ob_end_clean();
        QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 0, true);


        $var = $result->count();

        //CREACION DEL PDF
        require(sfConfig::get('sf_lib_dir') . '/vendor/fpdf17/fpdf.php');
        $pdf = new FPDF("P", "mm", array(430, 300));
        $pdf->SetFont('Helvetica', '', 11);
        $num_paginas = $var % 12 == 0 ? floor($var / 12) : floor($var / 12) + 1;
        for ($i = 0; $num_paginas > $i; $i++) {
            $pdf->AddPage();
            $pdf->SetXY(5, 5);
            $promocode = $result[0]->getPromoCode()->getAlphaId();
            $num_qr_por_columna = is_int(($var - (12 * $i)) / 2) ? ($var - (12 * $i)) / 2 : floor(($var - (12 * $i)) / 2) + 1;
            $j = 0;
            while ($j < $num_qr_por_columna && ($j < 6)) {
                $codigo0 = $result[$j * 2 + $i * 12]->getCode();
                $codigo1 = $result[$j * 2 + 1 + $i * 12]->getCode();
                $serial0 = str_pad($result[$j * 2 + $i * 12]->getPromoCode()->getSerial(), 5, '0', STR_PAD_LEFT) . '-' . str_pad($result[$j * 2 + $i * 12]->getSerial(), 5, '0', STR_PAD_LEFT);
                $serial1 = str_pad($result[$j * 2 + 1 + $i * 12]->getPromoCode()->getSerial(), 5, '0', STR_PAD_LEFT) . '-' . str_pad($result[$j * 2 + 1 + $i * 12]->getSerial(), 5, '0', STR_PAD_LEFT);
                $data = $data . '' . $result[$j * 2 + $i * 12]->getPromoCode()->getAlphaId();
                $pdf->Image(sfConfig::get('sf_web_dir') . '/images/cupon-cv-qr.jpg', 5, 5 + ($j * 62), 135, 62);
                $pdf->Image(sfConfig::get('sf_data_dir') . '/qr-codes/temp.png', 90.5, 9 + ($j * 62), 0, 0, 'PNG');
                if (!($var - (12 * $i) & 1) || ($j != $num_qr_por_columna - 1)) {
                    $data = $data . '' . $result[$j * 2 + 1 + $i * 12]->getPromoCode()->getAlphaId();
                    $pdf->Image(sfConfig::get('sf_web_dir') . '/images/cupon-cv-qr.jpg', 140, 5 + ($j * 62), 135, 62);
                    $pdf->Image(sfConfig::get('sf_data_dir') . '/qr-codes/temp.png', 225.5, 9 + ($j * 62), 0, 0, 'PNG');
                }
                $pdf->SetXY(5, 5 + ($j * 62));
                $pdf->Cell(55, 50, '', 0, 0);
                $pdf->Cell(40, 50, '', 0, 0);
                $pdf->Cell(40, 50, '', 0, 0);

                $pdf->SetXY(140, 5 + ($j * 62));
                $pdf->Cell(55, 50, '', 0, 0);
                $pdf->Cell(40, 50, '', 0, 0);
                $pdf->Cell(40, 50, '', 0, 1);

                $pdf->SetTextColor(176, 28, 44);
                $pdf->SetX($pdf->GetX() - 5);
                $pdf->Cell(55, 12, $serial0, 0, 0, 'C');
                $pdf->Cell(40, 12, '', 0, 0, 'C');
                $pdf->SetTextColor(82, 82, 84);
                $pdf->Cell(40, 12, $codigo0, 0, 0, 'C');

                if (!($var - (12 * $i) & 1) || ($j != $num_qr_por_columna - 1)) {
                    $pdf->SetTextColor(176, 28, 44);
                    $pdf->SetX(140);
                    $pdf->Cell(55, 12, $serial1, 0, 0, 'C');
                    $pdf->Cell(40, 12, '', 0, 0);
                    $pdf->SetTextColor(82, 82, 84);
                    $pdf->Cell(40, 12, $codigo1, 0, 0, 'C');
                }
                $j++;
            }
        }

        $pdf->Output();
        return sfView::NONE;
    }

    public function executeBatchAssign(sfWebRequest $request) {
        $ids = $request->getParameter('ids');

        $this->redirect('assign_vc', $ids);
    }

}
