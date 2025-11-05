<?php
include '../config.php';
$idEmpresa = intval($_POST['idEmpresa']);
$data=[];

$sql = "SELECT * FROM items WHERE idEmpresa=$idEmpresa AND idTipo=2 AND estado IN(0,1) ORDER BY nombre ASC";
$res = $link->query($sql);
$c=1;
while($row=$res->fetch_assoc()){
    $estado = $row['estado']==1 ? '<span class="badge bg-primary">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>';
    $data[]=[
        'contador'=>$c++,
        'codigo'=>$row['id'],
        'nombre'=>htmlspecialchars($row['nombre']),
        'precio'=>'Gs. '.number_format($row['precio'],2,'.','.'),
        'duracion'=>$row['duracion'],
        'estado'=>$estado,
        'idEstado'=>$row['estado']
    ];
}
echo json_encode(['data'=>$data]);
$link->close();
?>