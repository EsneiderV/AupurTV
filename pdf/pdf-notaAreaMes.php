<?php
require('fpdf/fpdf.php');
require('../models/Conexion.php');
require('../controllers/php/funciones.php');


if (isset($_GET['area']) && isset($_GET['mes'])) {
    $area = $_GET['area'];
    $mes = $_GET['mes'];

        class pdf extends FPDF
        {

            public $conexion;
            public $id;
            public $mes;

            function __construct($orientation='P',$unit='mm',$size='A4',$utf8=false,$conexion,$id,$mes)
            {
                parent::__construct($orientation,$unit,$size,$utf8);
                $this->conexion = $conexion;     
                $this->id = $id;       
                $this->mes = $mes;       
            }

            public function header()
            {
                $this->SetFont('Arial', 'B', 12);
                $nombreArea = mostrarelmesporid($this->id,$this->conexion);
                $mesNom = retornarmesNumero($this->mes);
                $mostrarTitulo = 'Calificaciones '.$nombreArea[0].' '.$mesNom;
                $mostrarTitulo = strtoupper($mostrarTitulo);
                // $this->Image('../image/headerpdf.jpg', 115, 3, 100, 20, 'jpg');
                $this->Image('../image/membretecarta.jpg', 0, 0, 216, 280, 'jpg');
                $this->Ln(20);
                $this->sety(40);
                $this->Cell(0, 0, $mostrarTitulo, 0, 1, 'C', 0);
                $this->sety(60);
                $this->setX(25);
                $preguntas = sacarPreguntasDiagrama($this->conexion);

                $i =0;
                while($pregunta = mysqli_fetch_array($preguntas)){
                    $i = $i +1;
                if($i % 3 == 0){
                    $this->Cell(55, 8, $pregunta['id'].'. '.$pregunta['pregunta'], 0, 1, 'C');
                    $this->setX(25);
                }else{
                    $this->Cell(55, 8, $pregunta['id'].'. '.$pregunta['pregunta'], 0, 0, 'C');
                }
                    
                }

                $this->sety(90);
                $preguntas = sacarPreguntasDiagrama($this->conexion);
                $this->setX(20);
                $this->SetFillColor(0,171,57); 
                $this->SetTextColor(255,255,255);
                $this->Cell(45, 8,'Empleado', 1, 0, 'C', 1);
                $this->SetFillColor(0,117,188);
                $this->SetTextColor(0,0,0);
                while($pregunta = mysqli_fetch_array($preguntas)){
                    $nom = substr($pregunta['pregunta'],0,3);
                    $this->Cell(19, 8,$pregunta['id'].'. '.$nom, 1, 0, 'C', 1);
                }

            }

            public function footer()
            {
                $this->SetFont('Arial', 'B', 12);
                $this->AliasNbPages();
                // $this->Image('../image/footerpdf.jpg', 0, 258, 220, 22, 'jpg');

            }
        }

        $fpdf = new pdf('P', 'mm', 'letter', true,$conexion,$area,$mes);
        $fpdf->AddPage('PORTRAIT', 'letter');
        $fpdf->SetFont('Arial', '', 10);
        $totalPreguntas = totalPreguntas($conexion);
        $nombrePersonaNotas = consultapreguntamespersonapdf($mes,$area,$conexion);
        $fpdf->Ln();
        $j=0;
        $fpdf->setX(20);
        $fpdf->SetFillColor(243,107,15); 
        while ($nombrePersonaNota = mysqli_fetch_array($nombrePersonaNotas)) {
            $j = $j + 1;
            if($j ===1){
                $fpdf->Cell(45, 8,$nombrePersonaNota['nombre'], 1, 0, 'C',1);   
            }
            if($j < $totalPreguntas[0]){
                $fpdf->Cell(19, 8,$nombrePersonaNota['nota'], 1, 0, 'C');
            }
            if($j == $totalPreguntas[0]){
                $fpdf->Cell(19, 8,$nombrePersonaNota['nota'], 1, 1, 'C');
                $j =0;
                $fpdf->setX(20);
            }
        }

        
        $fpdf->Close();
        $fpdf->Output('I', 'Inventario General.pdf', true);
    }


?> 
