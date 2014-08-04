<?php

require_once dirname(__FILE__) . '/../lib/promo_code_adminGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/promo_code_adminGeneratorHelper.class.php';

/**
 * promo_code_admin actions.
 *
 * @package    elperro
 * @subpackage promo_code_admin
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class promo_code_adminActions extends autoPromo_code_adminActions {

    public function executeListValidationCode(sfWebRequest $request) {
        $this->form = new epActivationAdminForm(array(), array('promocode_id' => $request->getParameter('id')));
        //$this->redirect('prueba');
    }

    public function executeAssignValidationCode(sfWebRequest $request) {
        $ids = $request->getParameter('ids');
        $promo_code_id = 1;
        $q = Doctrine_Query::create()
                ->update('ValidationCode vc')
                ->set('vc.promoCode_id', '?', $promo_code_id)
                ->whereIn('vc.id', $ids);
        $q->execute();
    }

    public function executeListPrintTickets(sfWebRequest $request) {
        $id = $request->getParameter('id');
        $q2 = Doctrine_Query::create()
                ->select('vc.id')
                ->from('ValidationCode vc')
                ->where('vc.promo_code_id=?', $id);
        $collection = $q2->execute();
        if ($collection->count() == 0) {
            echo "No hay tickets asignados a ese codigo de promocion";
        } else {
            $ids = array();
            foreach ($collection as $unit) {
                array_push($ids, $unit);
            }
            $q = Doctrine_Query::create()
                    ->select('vc.code, pc.serial,pc.alpha_id,vc.serial')
                    ->from('ValidationCode vc')
                    ->leftJoin('vc.PromoCode pc')
                    ->whereIn('vc.id', $ids);

            $result = $q->execute();
            $slug = $result[0]->getPromoCode()->getPromo()->getAffiliate()->getSlug();
            $this->setLayout(false);
//            ob_end_clean();
            // CARACTERISTICAS DEL QR
            require(sfConfig::get('sf_lib_dir') . '/vendor/phpqrcode/qrlib.php');
            $filename = sfConfig::get('sf_data_dir') . '/qr-codes/temp.png';
            $data = "http://www.lealtag.com/" . $result[0]->getPromoCode()->getAlphaId();
            $errorCorrectionLevel = "L";

            //if($slug=="froyogur"){
            if ($slug == "tudescuenton") {
                $matrixPointSize = "5";
            } else {
                $matrixPointSize = "5";
            }

            // GENERACION DEL CODIGO QR
            ob_end_clean();
            QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 0, true);

            $var = $result->count();

            //CREACION DEL PDF
            require(sfConfig::get('sf_lib_dir') . '/vendor/fpdf17/fpdf.php');
            $qr_por_pagina = 24;
            $pdf = new FPDF("P", "mm", array(440, 300));
            $pdf->SetFont('Helvetica', '', 11);
            $num_paginas = $var % $qr_por_pagina == 0 ? floor($var / $qr_por_pagina) : floor($var / $qr_por_pagina) + 1;
            for ($i = 0; $num_paginas > $i; $i++) {
                $pdf->AddPage();
                $pdf->SetXY(5, 5);
                $promocode = $result[0]->getPromoCode()->getAlphaId();
                $num_qr_por_columna = is_int(($var - ($qr_por_pagina * $i)) / 3) ? ($var - ($qr_por_pagina * $i)) / 3 : floor(($var - ($qr_por_pagina * $i)) / 3) + 1;
                $j = 0;

                while ($j < $num_qr_por_columna && ($j < 8)) {
                    $codigo0 = $result[$j * 3 + $i * $qr_por_pagina]->getCode();
                    $codigo1 = $result[$j * 3 + 1 + $i * $qr_por_pagina]->getCode();
                    $codigo2 = $result[$j * 3 + 2 + $i * $qr_por_pagina]->getCode();
                    $serial0 = str_pad($result[$j * 3 + $i * $qr_por_pagina]->getPromoCode()->getSerial(), 5, '0', STR_PAD_LEFT) . '-' . str_pad($result[$j * 3 + $i * $qr_por_pagina]->getSerial(), 5, '0', STR_PAD_LEFT);
                    $serial1 = str_pad($result[$j * 3 + 1 + $i * $qr_por_pagina]->getPromoCode()->getSerial(), 5, '0', STR_PAD_LEFT) . '-' . str_pad($result[$j * 3 + 1 + $i * $qr_por_pagina]->getSerial(), 5, '0', STR_PAD_LEFT);
                    $serial2 = str_pad($result[$j * 3 + 2 + $i * $qr_por_pagina]->getPromoCode()->getSerial(), 5, '0', STR_PAD_LEFT) . '-' . str_pad($result[$j * 3 + 2 + $i * $qr_por_pagina]->getSerial(), 5, '0', STR_PAD_LEFT);

                    $data = $data . '' . $result[$j * 3 + $i * $qr_por_pagina]->getPromoCode()->getAlphaId();
                    if ($slug == 'froyogur') {
                        $pdf->Image(sfConfig::get('sf_web_dir') . '/images/cupon-cv-qr-froyo-small.png', 5, 5 + ($j * 51), 85, 52.5);
                    } else {
                        $pdf->Image(sfConfig::get('sf_web_dir') . '/images/cupon-cv-qr-small.png', 5, 5 + ($j * 51), 85, 50);
                    }
                    $pdf->Image(sfConfig::get('sf_data_dir') . '/qr-codes/temp.png', 49, 8 + ($j * 51), 0, 0, 'PNG');

                    if (!($var - ($qr_por_pagina * $i) & 1) || ($j != $num_qr_por_columna - 2)) {
                        $data = $data . '' . $result[$j * 3 + 1 + $i * $qr_por_pagina]->getPromoCode()->getAlphaId();
                        if ($slug == 'froyogur') {
                            $pdf->Image(sfConfig::get('sf_web_dir') . '/images/cupon-cv-qr-froyo-small.png', 91, 5 + ($j * 51), 85, 52.5);
                        } else {
                            $pdf->Image(sfConfig::get('sf_web_dir') . '/images/cupon-cv-qr-small.png', 91, 5 + ($j * 51), 85, 50);
                        }
                        $pdf->Image(sfConfig::get('sf_data_dir') . '/qr-codes/temp.png', 135, 8 + ($j * 51), 0, 0, 'PNG');
                    }

                    if (!($var - ($qr_por_pagina * $i) & 1) || ($j != $num_qr_por_columna - 1)) {
                        $data = $data . '' . $result[$j * 3 + 2 + $i * $qr_por_pagina]->getPromoCode()->getAlphaId();
                        if ($slug == 'froyogur') {
                            $pdf->Image(sfConfig::get('sf_web_dir') . '/images/cupon-cv-qr-froyo-small.png', 177, 5 + ($j * 51), 85, 52.5);
                        } else {
                            $pdf->Image(sfConfig::get('sf_web_dir') . '/images/cupon-cv-qr-small.png', 177, 5 + ($j * 51), 85, 50);
                        }
                        $pdf->Image(sfConfig::get('sf_data_dir') . '/qr-codes/temp.png', 221, 8 + ($j * 51), 0, 0, 'PNG');
                    }

//                    COMENTADO POR SI A CASO SE NECESITAN LOS CALCULOS DE LOS NUMEROS GRANDES
//                    if (!($var - ($qr_por_pagina * $i) & 1) || ($j != $num_qr_por_columna - 1)) {
//                        $data = $data . '' . $result[$j * 3 + 2 + $i * $qr_por_pagina]->getPromoCode()->getAlphaId();
//                        if($slug == 'froyogur'){
//                            $pdf->Image(sfConfig::get('sf_web_dir') . '/images/cupon-cv-qr-froyo-small.png', 140, 6 + ($j * 62), 135, 62);
//                        }
//                        
                    //PRIMERA COLUMNA DE CUADROS GUIA
                    $pdf->SetXY(5, 5 + ($j * 51));
                    $pdf->Cell(45, 40, '', 0, 0);
                    $pdf->Cell(10, 40, '', 0, 0);
                    $pdf->Cell(30, 40, '', 0, 0);

                    //SEGUNDA COLUMNA DE CUADROS GUIA
                    $pdf->SetXY(91, 5 + ($j * 51));
                    $pdf->Cell(45, 40, '', 0, 0);
                    $pdf->Cell(10, 40, '', 0, 0);
                    $pdf->Cell(30, 40, '', 0, 0);

                    //TERCERA COLUMNA DE CUADROS GUIA
                    $pdf->SetXY(177, 5 + ($j * 51));
                    $pdf->Cell(45, 40, '', 0, 0);
                    $pdf->Cell(10, 40, '', 0, 0);
                    $pdf->Cell(30, 40, '', 0, 1);

                    //PRIMERA COLUMNA DE CODIGOS Y SERIALES
                    $pdf->SetTextColor(176, 28, 44);
                    $pdf->SetX($pdf->GetX() - 5);
                    $pdf->Cell(45, 12, $serial0, 0, 0, 'C');
                    $pdf->Cell(10, 12, '', 0, 0, 'C');
                    $pdf->SetTextColor(82, 82, 84);
                    $pdf->Cell(30, 12, $codigo0, 0, 0, 'C');

                    //SEGUNDA COLUMNA DE CODIGOS Y SERIALES
                    if (!($var - ($qr_por_pagina * $i) & 1) || ($j != $num_qr_por_columna - 2)) {
                        $pdf->SetTextColor(176, 28, 44);
                        $pdf->SetX(91);
                        $pdf->Cell(45, 12, $serial1, 0, 0, 'C');
                        $pdf->Cell(10, 12, '', 0, 0);
                        $pdf->SetTextColor(82, 82, 84);
                        $pdf->Cell(30, 12, $codigo1, 0, 0, 'C');
                    }

                    //TERCERA COLUMNA DE CODIGOS Y SERIALES
                    if (!($var - ($qr_por_pagina * $i) & 1) || ($j != $num_qr_por_columna - 1)) {
                        $pdf->SetTextColor(176, 28, 44);
                        $pdf->SetX(177);
                        $pdf->Cell(45, 12, $serial2, 0, 0, 'C');
                        $pdf->Cell(10, 12, '', 0, 0);
                        $pdf->SetTextColor(82, 82, 84);
                        $pdf->Cell(30, 12, $codigo2, 0, 0, 'C');
                    }
                    $j++;
                }
            }

            $pdf->Output();
            return sfView::NONE;
        }
    }

    public function executePrueba(sfWebRequest $request) {
        $collection = new Doctrine_Collection('ValidationCode');
        $q2 = Doctrine_Query::create()
                ->select('pc.id')
                ->from('PromoCode pc')
                ->whereIn('pc.id', array(137));
//                ->where('pc.status=?', 'unassigned')
//                ->andWhere('pc.serial>?', 1000)
//                ->andWhere('pc.type=?', 'validation_required');
        $promoCodes = $q2->execute();
        $id = "584";
        foreach ($promoCodes as $promocode) {
            echo "INSERT INTO `elperro_db`.`validation_code` (`serial`, `code`, `promo_code_id`) VALUES</br>";
//            echo $promocode->getSerial() . "</br>";
            echo "('" . 0 . "', '" . Util::GenSecret(5, 0) . "', '" . "584" . "')";
            for ($i = 1; $i < 5000; $i++) {
                if ($i % 150 == 0) {
                    echo ";<br>INSERT INTO `elperro_db`.`validation_code` (`serial`, `code`, `promo_code_id`) VALUES</br>";
                    echo "('" . $i . "', '" . Util::GenSecret(5, 0) . "', '" . $id . "')";
                } else {
                    echo ",<br>('" . $i . "', '" . Util::GenSecret(5, 0) . "', '" . $id . "')";
                }
            }
            echo ";";

//            $collection->save();
        }
    }

    protected function buildQuery() {
        $tableMethod = $this->configuration->getTableMethod();
        if (is_null($this->filters)) {
            $this->filters = $this->configuration->getFilterForm($this->getFilters());
        }

        $this->filters->setTableMethod($tableMethod);

        $query = $this->filters->buildQuery($this->getFilters());

        $this->addSortQuery($query);

        $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);
        $query = $event->getReturnValue();
        
        //$query = Doctrine_Query::create();
        
        return $query; //->setHydrationMode(Doctrine::h);
    }

}
