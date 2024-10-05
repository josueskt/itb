<?php
require('../fpdf/fpdf.php'); // Incluye la librería FPDF

// Obtener el usuario_id desde la URL o desde el localStorage (si lo envías desde JavaScript)
$usuario_id = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : null;

// Verificamos si el usuario_id fue recibido
if (!$usuario_id) {
    die('El ID de usuario no fue proporcionado.');
}

// Crear una instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage(); // Añadir una página

// Cargar una fuente que soporte UTF-8 (puedes usar Arial o alguna fuente TrueType)
$pdf->SetFont('Arial', '', 12);

// Incluir la conexión a la base de datos
require('../db.php');

// Consulta para obtener el nombre del estudiante y los cursos en los que está matriculado
$sql_usuario = "SELECT usuarios.nombre 
                FROM usuarios 
                WHERE usuarios.id = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param('i', $usuario_id); 
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();

// Verificar si el estudiante fue encontrado
if ($result_usuario->num_rows > 0) {
    $usuario = $result_usuario->fetch_assoc();
    $nombre_estudiante = utf8_decode($usuario['nombre']); // Decodificamos para mostrar correctamente
} else {
    die('No se encontró el usuario en la base de datos.');
}

// Encabezado de la matrícula
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 10, utf8_decode('Comprobante de Matrícula'), 0, 1, 'C');
$pdf->Ln(5);

// Información del estudiante
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Estimado estudiante: ' . $nombre_estudiante), 0, 1, 'C');
$pdf->Ln(10);

// Texto explicativo
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 10, utf8_decode("Con este comprobante, se certifica que ha sido matriculado con éxito en los siguientes cursos. A continuación se presenta un resumen de su inscripción en el sistema."));
$pdf->Ln(10);

// Título de la tabla "Matriculación"
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Matriculación'), 0, 1, 'C');
$pdf->Ln(5);

// Encabezado de la tabla de cursos
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, utf8_decode('Curso'), 1, 0, 'C');
$pdf->Cell(50, 10, utf8_decode('Fecha de Inscripción'), 1, 1, 'C');

// Consulta para obtener los cursos en los que el estudiante está matriculado
$sql_cursos = "SELECT cursos.nombre AS curso, fecha_inscripcion
               FROM matriculas
               INNER JOIN cursos ON matriculas.curso_id = cursos.id
               WHERE matriculas.usuario_id = ?
               ORDER BY matriculas.fecha_inscripcion DESC";
$stmt_cursos = $conn->prepare($sql_cursos);
$stmt_cursos->bind_param('i', $usuario_id);
$stmt_cursos->execute();
$result_cursos = $stmt_cursos->get_result();

// Verificar si el estudiante está matriculado en algún curso
if ($result_cursos->num_rows > 0) {
    // Mostrar los cursos en los que el estudiante está matriculado
    $pdf->SetFont('Arial', '', 10);
    while ($row = $result_cursos->fetch_assoc()) {
        $pdf->Cell(30, 10, utf8_decode($row['curso']), 1, 0, 'L');
        $pdf->Cell(50, 10, utf8_decode($row['fecha_inscripcion']), 1, 1, 'C');
    }
} else {
    // Si el estudiante no está matriculado en ningún curso
    $pdf->Cell(0, 10, utf8_decode('No se encontraron cursos para este estudiante.'), 0, 1, 'C');
}

// Agregar un pie de página con un mensaje adicional
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->MultiCell(0, 10, utf8_decode("Este documento es un comprobante oficial de matrícula. Si tiene alguna pregunta, por favor contacte a la administración de la institución."));

// Cerrar el documento y enviarlo al navegador
$pdf->Output('reporte_matricula.pdf', 'I'); // 'I' es para mostrarlo en el navegador
?>
