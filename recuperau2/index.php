<?php
//Morellano
header('Content-Type:application/json');
$metodo = $_SERVER["REQUEST_METHOD"];
switch($metodo){
    case 'GET':
        if($_GET['accion']=='docente'){
            try{
                $conn = new PDO("mysql:host=localhost;dbname=utez3;charset=utf8", "root", "");
            }catch(PDOException $e){
                echo $e -> getMessage();
            }

            if(isset($_GET['id'])){
                $pstm= $conn->prepare("SELECT * FROM docente WHERE numeroEmpleado = :numeroEmpleado");
                $pstm->bindParam(':numeroEmpleado',$_GET['id']);
                $pstm->execute();
                $rs=$pstm->fetchAll(PDO::FETCH_ASSOC);
                if($rs != null){
                    echo json_encode($rs[0], JSON_PRETTY_PRINT);
                }else{
                    echo "No se encontraron coincidencia";
                }
            } else {
                $pstm=$conn->prepare("SELECT * FROM docente");
                $pstm->execute();
                $rs=$pstm->fetchAll(PDO::FETCH_ASSOC);
                if($rs != null){
                    echo json_encode($rs, JSON_PRETTY_PRINT);
                }else{
                    echo "No hay registro";
                }
            }
        }

        if($_GET['accion'] == 'alumno'){
            try {
                $conn = new PDO("mysql:host=localhost;dbname=utez3;charset=utf8", "root", "");
            } catch (PDOException $e) {
                echo $e -> getMessage();
            }

            if(isset($_GET['id'])){
                $pstm= $conn->prepare("SELECT * FROM alumno WHERE matricula = :matricula");
                $pstm->bindParam(':matricula',$_GET['id']);
                $pstm->execute();
                $rs=$pstm->fetchAll(PDO::FETCH_ASSOC);
                if($rs != null){
                    echo json_encode($rs[0], JSON_PRETTY_PRINT);
                } else {
                    echo "No se encontraron coincidencia";
                }
            } else {
                $pstm = $conn -> prepare("SELECT * FROM alumno");
                $pstm -> execute();
                $rs = $pstm -> fetchAll(PDO::FETCH_ASSOC);
                if($rs != null){
                    echo json_encode($rs, JSON_PRETTY_PRINT);
                }else{
                    echo "No hay registro";
                }
            }
        }

        if($_GET['accion'] == 'calificacion'){
            try {
                $conn = new PDO("mysql:host=localhost;dbname=utez3;charset=utf8", "root", "");
            } catch (PDOException $e) {
                echo $e -> getMessage();
            }

            if(isset($_GET['id'])){
                $pstm= $conn->prepare("SELECT c.*,a.nombre FROM calificacion c INNER JOIN alumno a ON c.matriculaAlumno = a.matricula WHERE c.matriculaAlumno = :matricula");
                $pstm->bindParam(':matricula',$_GET['id']);
                $pstm->execute();
                $rs=$pstm->fetchAll(PDO::FETCH_ASSOC);
                if($rs != null){
                    echo json_encode($rs[0], JSON_PRETTY_PRINT);
                }else{
                    echo "No se encontraron coincidencias";
                }
            }else{
                $pstm = $conn -> prepare("SELECT * FROM calificacion");
                $pstm -> execute();
                $rs = $pstm -> fetchAll(PDO::FETCH_ASSOC);
                if($rs != null){
                    echo json_encode($rs, JSON_PRETTY_PRINT);
                }else{
                    echo "No hay registro";
                }
            }
        }

        if($_GET['accion'] == 'promedio'){
            try {
                $conn = new PDO("mysql:host=localhost;dbname=utez3;charset=utf8", "root", "");
            } catch (PDOException $e) {
                echo $e -> getMessage();
            }
            $pstm = $conn->prepare("SELECT AVG(ALL calificacion) FROM calificacion;");
            $pstm -> execute();
            $rs = $pstm -> fetchAll(PDO::FETCH_ASSOC);
            if($rs != null){
                echo json_encode($rs, JSON_PRETTY_PRINT);
            }else{
                echo "No hay registro";
            }
        }
        break;
    case 'POST':
        if($_GET['accion'] == 'docente'){
            $json_data = json_decode(file_get_contents('php://input'));
            try {
                $conn = new PDO("mysql:host=localhost;dbname=utez3;charset=utf8", "root", "");
            } catch (PDOException $e) {
                echo $e -> getMessage();
            }
            $pstm = $conn ->prepare("INSERT INTO docente (nombre, apellidos, fechaNac, curp) 
            VALUES (:nombre,:apellidos,:fechaNac,:curp)");
            $pstm -> bindParam(':nombre', $json_data->nombre);
            $pstm -> bindParam(':apellidos', $json_data->apellidos);
            $pstm -> bindParam(':fechaNac', $json_data->fechaNac);
            $pstm -> bindParam(':curp',$json_data->curp);
            $rs = $pstm -> execute();
            // $pstm = $conn -> prepare("SELECT d.curp FROM docente d");
            // $dif = $pstm-> execute();
            if($rs){
                $_POST['error'] = false;
                $_POST['message'] = "Docente registrado";
                $_POST['status'] = 200;
            }else{
                $_POST['error'] = true;
                $_POST['message'] = "Error al registrar Docente";
                $_POST['status'] = 400;
            }
            echo json_encode($_POST);
        }

        if($_GET['accion'] == 'alumno'){
            $json_data = json_decode(file_get_contents('php://input'));
            try{
                $conn = new PDO("mysql:host=localhost;dbname=utez3;charset=utf8", "root", "");
            }catch(PDOException $e){
                echo $e -> getMessage();
            }
            $pstm = $conn -> prepare("INSERT INTO alumno(nombre, apellidos, fechaNac,curp,matricula)
            VALUES (:nombre,:apellidos,:fechaNac,:curp,:matricula)");
            $pstm -> bindParam(':nombre', $json_data->nombre);
            $pstm -> bindParam(':apellidos', $json_data->apellidos);
            $pstm -> bindParam(':fechaNac', $json_data->fechaNac);
            $pstm -> bindParam(':curp', $json_data->curp);
            $pstm -> bindParam (':matricula', $json_data->matricula);
            $rs = $pstm -> execute();
            if($rs){
                $_POST['error'] = false;
                $_POST['message'] = "Alumno registrado";
                $_POST['status'] = 200;
            }else{
                $_POST['error'] = true;
                $_POST['message'] = "Error al registrar Alumno";
                $_POST['status'] = 400;
            }
            echo json_encode($_POST);
        }

        if($_GET['accion'] == 'calificacion'){
            $json_data = json_decode(file_get_contents('php://input'));
            try{
                $conn = new PDO("mysql:host=localhost;dbname=utez3;charset=utf8", "root", "");
            }catch(PDOException $e){
                echo $e -> getMessage();
            }
            $pstm = $conn -> prepare("INSERT INTO calificacion(materia,calificacion,matriculaAlumno)
            VALUES(:materia,:calificacion,:matriculaAlumno)");
            $pstm -> bindParam(':materia', $json_data -> materia);
            $pstm -> bindParam(':calificacion', $json_data -> calificacion);
            $pstm -> bindParam(':matriculaAlumno', $json_data -> matriculaAlumno);
            $rs = $pstm -> execute();
            if($rs){
                $_POST['error'] = false;
                $_POST['message'] = "Calificacion registrada";
                $_POST['status'] = 200;
            }else{
                $_POST['error'] = true;
                $_POST['message'] = "Error al registrar calificacion";
                $_POST['status'] = 400;
            }
            echo json_encode($_POST);
        }
        break;
    case 'PUT':
        if($_GET['accion'] == 'docente'){
            $json_data = json_decode(file_get_contents('php://input'));
            try{
                $conn = new PDO("mysql:host=localhost;dbname=utez3;charset=utf8", "root", "");
            }catch(PDOException $e){
                echo $e -> getMessage();
            }
            $pstm = $conn -> prepare("UPDATE docente SET nombre = :nombre, apellidos=:apellidos WHERE numeroEmpleado = :numeroEmpleado");
            $pstm -> bindParam(':nombre', $json_data->nombre);
            $pstm -> bindParam(':apellidos', $json_data->apellidos);
            $pstm -> bindParam(':numeroEmpleado', $json_data->numeroEmpleado);
            $rs = $pstm -> execute();
            if($rs){
                echo "Exito al actualizar Docente", $rs;
            }else{
                echo "Error al actualizar Docente", $rs;
            }
        }

        if($_GET['accion'] == 'alumno'){
            $json_data = json_decode(file_get_contents('php://input'));
            try{
                $conn = new PDO("mysql:host=localhost;dbname=utez3;charset=utf8", "root", "");
            }catch(PDOException $e){
                echo $e -> getMessage();
            }
            $pstm = $conn -> prepare("UPDATE alumno SET nombre=:nombre, apellidos=:apellidos WHERE matricula = :matricula");
            $pstm -> bindParam(':nombre', $json_data->nombre);
            $pstm -> bindParam(':apellidos', $json_data->apellidos);
            $pstm -> bindParam(':matricula', $json_data->matricula);
            $rs = $pstm -> execute();
            if($rs){
                echo "Exito al actualizar Alumno", $rs;
            }else{
                echo "Error al actualizar Alumno", $rs;
            }
        }
        break;
}