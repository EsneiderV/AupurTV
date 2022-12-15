<?php
require('fpdf/fpdf.php');
require('../models/Conexion.php');
require('../controllers/php/funciones.php');


if (isset($_GET['anio']) && isset($_GET['mes'])) {
    $anio = $_GET['anio'];
    $mes = $_GET['mes'];

    class pdf extends FPDF
    {

        public $conexion;
        public $id;
        public $mes;
        public $anio;

        function __construct($orientation = 'P', $unit = 'mm', $size = 'A4', $utf8 = false, $conexion, $id, $mes,$anio)
        {
            parent::__construct($orientation, $unit, $size, $utf8);
            $this->conexion = $conexion;
            $this->id = $id;
            $this->mes = $mes;
            $this->anio = $anio;
        }

        public function header()
        {
            $this->SetFont('Arial', 'B', 15);
            // $this->Image('../image/headerpdf.jpg', 115, 3, 100, 20, 'jpg');
            $this->Image('../image/membretecarta.jpg', 0, 0, 216, 280, 'jpg');
            $this->Ln(20);
            $this->sety(40);
            $this->Cell(0, 0,"Calificaciones empleados ".retornarmesNumero($this->mes).' '.$this->anio, 0, 1, 'C', 0);
            $this->sety(60);
            $this->setX(25);
            $this->sety(55);

            $this->SetFont('Arial', 'B', 10);
            $preguntas = consultapreguntageneralmespersonapdf($this->conexion);
            $this->setX(20);
            // $this->SetFillColor(0, 171, 57);
            // $this->SetTextColor(255, 255, 255);
            $this->Cell(35, 8, 'Empleado', 1, 0, 'C');
            // $this->SetFillColor(0, 117, 188);
            // $this->SetTextColor(0, 0, 0);
            $this->SetFont('Arial', 'B', 10);
            while ($pregunta = mysqli_fetch_array($preguntas)) {
                $this->Cell(40, 8, utf8_decode($pregunta['pregunta']), 1, 0, 'C');
            }
            $this->Cell(20, 8, "Total", 1, 1, 'C');
        }

        public function footer()
        {
            $this->SetFont('Arial', 'B', 12);
            $this->AliasNbPages();
            // $this->Image('../image/footerpdf.jpg', 0, 258, 220, 22, 'jpg');

        }
    }

    $fpdf = new pdf('P', 'mm', 'letter', true, $conexion, $area, $mes,$anio);
    $fpdf->AddPage('PORTRAIT', 'letter');
    $fpdf->SetFont('Arial', '', 10);
   
    $usuarios = usuario($conexion);
    $areamostrar = 0;
    while ($usuario = mysqli_fetch_array($usuarios)) {

        $fpdf->SetFillColor(170, 170, 170);

        $fpdf->setX(20);
        if($areamostrar != $usuario['idArea']){
            $fpdf->Cell(175, 8, $usuario['area'], 1, 1, 'C',1);
            $areamostrar = $usuario['idArea'];
        }

        
        $fpdf->setX(20);
        $apellido = explode(' ',$usuario['apellidos']) ;
        $letraApellido = substr($apellido[1],0, 1);
        $letraApellido = strtoupper($letraApellido);

        
        $nombre = explode(' ',$usuario['nombre']);
        $nombre = $nombre[0];
        $nombreCompleto = $nombre.' '.$apellido[0].' '.$letraApellido;
        $fpdf->Cell(35, 8,utf8_decode($nombreCompleto) , 1, 0, 'C');

      $calificaciones = empleadosCalificaciones($usuario['id'],$mes,$anio,$conexion);
      while ($calificacion = mysqli_fetch_array($calificaciones)) {
        $fpdf->Cell(40, 8, $calificacion['nota'], 1, 0, 'C');
      }

      $promedio = empleadosCalificacionesPromedio($usuario['id'],$mes,$anio,$conexion);
      $fpdf->Cell(20, 8,number_format($promedio, 2), 1, 1, 'C');

    }

    $fpdf->Close();
    $fpdf->Output('I', 'CalificacionesEmpleados.pdf', true);
}
