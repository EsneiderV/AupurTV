
<?php
require('fpdf/fpdf.php');
require('../models/Conexion.php');
require('../controllers/php/funciones.php');

if (isset($_GET['area'])) {

    if ($_GET['area'] == '0') {

        class pdf extends FPDF
        {
            public function header()
            {
                $this->SetFont('Arial', 'B', 12);
                $this->Cell(0, 3, utf8_decode('AUPUT TV'), 0, 0, 'C');
                $this->Image('../image/logoNaranja.png', 179, 1, 40, 20, 'png');
                $this->Ln(20);
                $this->sety(30);
                $this->Cell(0, 0, 'Inventario general', 0, 1, 'C', 0);
                $this->sety(40);
                $this->Cell(19, 8, 'Codigo', 1, 0, 'C', 0);
                $this->Cell(40, 8, 'Nombre', 1, 0, 'C', 0);
                $this->Cell(25, 8, 'Estado', 1, 0, 'C', 0);
                $this->Cell(40, 8, 'Area', 1, 0, 'C', 0);
                $this->Cell(70, 8, 'Responsable', 1, 1, 'C', 0);
            }

            public function footer()
            {
                $this->SetFont('Arial', 'B', 12);
                $this->setY(-15);
                $this->AliasNbPages();
                $this->Cell(0, 3, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
            }
        }

        $fpdf = new pdf('P', 'mm', 'letter', true);
        $fpdf->AddPage('PORTRAIT', 'letter');
        $fpdf->SetFont('Arial', '', 12);
        $sinAsignar = mostrarInventarioPorAreaGeneralNoA($conexion);
        // Muesta los no asignados
        while ($sinAsignarItem = mysqli_fetch_array($sinAsignar)) {
            $fpdf->Cell(19, 8,  utf8_decode($sinAsignarItem['cod']), 1, 0, 'C', 0);
            $fpdf->Cell(40, 8, utf8_decode($sinAsignarItem['nombre']), 1, 0, 'C', 0);
            $fpdf->Cell(25, 8, utf8_decode($sinAsignarItem['estado']), 1, 0, 'C', 0);
            $fpdf->Cell(40, 8, utf8_decode('No asignado'), 1, 0, 'C', 0);
            $fpdf->Cell(70, 8, utf8_decode('No asignado'), 1, 1, 'C', 0);
        }
        $areas = mostrarAreaDirectorio($conexion);
        while ($area = mysqli_fetch_array($areas)) {

            $items =  mostrarInventarioPorArea($area['codigo'], $conexion);

            while ($item = mysqli_fetch_array($items)) {
                $fpdf->Cell(19, 8,  utf8_decode($item['cod']), 1, 0, 'C', 0);
                $fpdf->Cell(40, 8, utf8_decode($item['nombre']), 1, 0, 'C', 0);
                $fpdf->Cell(25, 8, utf8_decode($item['estado']), 1, 0, 'C', 0);
                $fpdf->Cell(40, 8, utf8_decode($item['nombre_area']), 1, 0, 'C', 0);
                $fpdf->Cell(70, 8, utf8_decode($item['nombre_responsable'].' '.$item['apellido_responsable']), 1, 1, 'C', 0);
            }
            $itemsG = mostrarInventarioPorAreaGeneral($area['codigo'], $conexion);
            while ($itemG = mysqli_fetch_array($itemsG)) {
                $fpdf->Cell(19, 8,  utf8_decode($itemG['cod']), 1, 0, 'C', 0);
                $fpdf->Cell(40, 8, utf8_decode($itemG['nombre']), 1, 0, 'C', 0);
                $fpdf->Cell(25, 8, utf8_decode($itemG['estado']), 1, 0, 'C', 0);
                $fpdf->Cell(40, 8, utf8_decode($itemG['nombre_area']), 1, 0, 'C', 0);
                $fpdf->Cell(70, 8, utf8_decode('No asignado'), 1, 1, 'C', 0);
            }
        }

        $fpdf->Close();
        $fpdf->Output('D', 'Inventario General.pdf', true);
    }

    if($_GET['area'] != '0'){

         $nombreArea =  mostrarInventarioPorArea($_GET['area'], $conexion);
         $nombreArea = mysqli_fetch_array($nombreArea);
        class pdf extends FPDF
        {

            public $nombre;

             function __construct($orientation='P', $unit='mm', $size='A4',$utf8 = false,$nombre) {
                 Parent::__construct($orientation,$unit,$size,$utf8);
                $this->nombre = $nombre;
            }
            
            public function header()
            {
                $this->SetFont('Arial', 'B', 12);
                $this->Cell(0, 3, utf8_decode('AUPUT TV'), 0, 0, 'C');
                $this->Image('../image/logoNaranja.png', 179, 1, 40, 20, 'png');
                $this->Ln(20);
                $this->sety(30);
                $this->Cell(0, 0, 'Inventario Area '.$this->nombre, 0, 1, 'C', 0);
                $this->sety(40);
                $this->Cell(19, 8, 'Codigo', 1, 0, 'C', 0);
                $this->Cell(40, 8, 'Nombre', 1, 0, 'C', 0);
                $this->Cell(25, 8, 'Estado', 1, 0, 'C', 0);
                $this->Cell(40, 8, 'Area', 1, 0, 'C', 0);
                $this->Cell(70, 8, 'Responsable', 1, 1, 'C', 0);
            }

            public function footer()
            {
                $this->SetFont('Arial', 'B', 12);
                $this->setY(-15);
                $this->AliasNbPages();
                $this->Cell(0, 3, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
            }
        }

        $fpdf = new pdf('P', 'mm', 'letter', true, $nombreArea['nombre_area']);
        $fpdf->AddPage('PORTRAIT', 'letter');
        $fpdf->SetFont('Arial', '', 12);

            $items =  mostrarInventarioPorArea($_GET['area'], $conexion);

            while ($item = mysqli_fetch_array($items)) {
                $fpdf->Cell(19, 8,  utf8_decode($item['cod']), 1, 0, 'C', 0);
                $fpdf->Cell(40, 8, utf8_decode($item['nombre']), 1, 0, 'C', 0);
                $fpdf->Cell(25, 8, utf8_decode($item['estado']), 1, 0, 'C', 0);
                $fpdf->Cell(40, 8, utf8_decode($item['nombre_area']), 1, 0, 'C', 0);
                $fpdf->Cell(70, 8, utf8_decode($item['nombre_responsable'].' '.$item['apellido_responsable']), 1, 1, 'C', 0);
            }

            $itemsG = mostrarInventarioPorAreaGeneral($_GET['area'], $conexion);
            while ($itemG = mysqli_fetch_array($itemsG)) {
                $fpdf->Cell(19, 8,  utf8_decode($itemG['cod']), 1, 0, 'C', 0);
                $fpdf->Cell(40, 8, utf8_decode($itemG['nombre']), 1, 0, 'C', 0);
                $fpdf->Cell(25, 8, utf8_decode($itemG['estado']), 1, 0, 'C', 0);
                $fpdf->Cell(40, 8, utf8_decode($itemG['nombre_area']), 1, 0, 'C', 0);
                $fpdf->Cell(70, 8, utf8_decode('No asignado'), 1, 1, 'C', 0);
            }
        
        $fpdf->Close();
        $fpdf->Output('D', 'Inventario Area '.$nombreArea['nombre_area'].'.pdf', true);

    }

}

?> 
