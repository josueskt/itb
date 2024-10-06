<?php
require('../fpdf/fpdf.php'); 


$usuario_id = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : null;


if (!$usuario_id) {
    die('El ID de usuario no fue proporcionado.');
}


$pdf = new FPDF();
$pdf->AddPage(); 


$pdf->SetFont('Arial', '', 12);


require('../db.php');


$sql_usuario = "SELECT usuarios.nombre 
                FROM usuarios 
                WHERE usuarios.id = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param('i', $usuario_id); 
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();


if ($result_usuario->num_rows > 0) {
    $usuario = $result_usuario->fetch_assoc();
    $nombre_estudiante = utf8_decode($usuario['nombre']); 
} else {
    die('No se encontró el usuario en la base de datos.');
}


$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 10, utf8_decode('Comprobante de Matrícula'), 0, 1, 'C');
$pdf->Ln(5);


$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Estimado estudiante: ' . $nombre_estudiante), 0, 1, 'C');
$pdf->Ln(10);


$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 10, utf8_decode("Con este comprobante, se certifica que ha sido matriculado con éxito en los siguientes cursos. A continuación se presenta un resumen de su inscripción en el sistema."));
$pdf->Ln(10);


$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Matriculación'), 0, 1, 'C');
$pdf->Ln(5);


$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, utf8_decode('Curso'), 1, 0, 'C');
$pdf->Cell(50, 10, utf8_decode('Fecha de Inscripción'), 1, 1, 'C');


$sql_cursos = "SELECT cursos.nombre AS curso, fecha_inscripcion
               FROM matriculas
               INNER JOIN cursos ON matriculas.curso_id = cursos.id
               WHERE matriculas.usuario_id = ?
               ORDER BY matriculas.fecha_inscripcion DESC";
$stmt_cursos = $conn->prepare($sql_cursos);
$stmt_cursos->bind_param('i', $usuario_id);
$stmt_cursos->execute();
$result_cursos = $stmt_cursos->get_result();


if ($result_cursos->num_rows > 0) {
    
    $pdf->SetFont('Arial', '', 10);
    while ($row = $result_cursos->fetch_assoc()) {
        $pdf->Cell(30, 10, utf8_decode($row['curso']), 1, 0, 'L');
        $pdf->Cell(50, 10, utf8_decode($row['fecha_inscripcion']), 1, 1, 'C');
    }
} else {
  
    $pdf->Cell(0, 10, utf8_decode('No se encontraron cursos para este estudiante.'), 0, 1, 'C');
}


$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->MultiCell(0, 10, utf8_decode("Este documento es un comprobante oficial de matrícula. Si tiene alguna pregunta, por favor contacte a la administración de la institución."));


$pdf->Output('reporte_matricula.pdf', 'I'); 
?>
