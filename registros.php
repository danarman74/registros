<?php
    switch ($_GET['funcion']) {
        case 'login':
            login($_GET);
        break;
        case 'nuevo':
            registro($_GET);
        break;
        case 'update':
            uptate($_GET);
        break;
        case 'delete':
            delete($_GET);
        break;
    }
    function login($param){
        include ("coneccion.php");
        include ("conect.php");
        $dbConn =  connect($db);
        $pass=md5($param['password']);
        $consulta="SELECT * FROM usuarios WHERE user='".$param['usr']."' and pass='".$pass."' and status='activo'";
        $sql = $dbConn->prepare($consulta);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $res=array();
        foreach ($sql->fetchAll() as $key => $value) {
            $res['nombre']=$value['nombre'];
            $res['usuario']=$value['user'];
            $res['estatus']=$value['status'];
            $_SESSION['usr']="Dani";
        }
        if ($res!=[]) {
            echo json_encode( $res  );
        }else{
            $response = array(
                "erro" => "usuario no se encuntraregistrado o esta dado de baja",
            );
            echo json_encode($response);
        }
    }
    function registro($param){
        include ("coneccion.php");
        include ("conect.php");
        $dbConn =  connect($db);
        $pass=md5($param['password']);
        $consulta="SELECT * FROM usuarios WHERE user='".$param['usr']."' and pass='".$pass."' and status='activo'";
        $sql = $dbConn->prepare($consulta);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $res=array();
        foreach ($sql->fetchAll() as $key => $value) {
            $res['nombre']=$value['nombre'];
            $res['usuario']=$value['user'];
            $res['estatus']=$value['status'];
        }
        if ($res!=[]) {
            $response = array(
                "erro" => "usuario ya se encuentra registrado",
            );
            echo json_encode($response);
        }else{
            $consulta="INSERT INTO usuarios(nombre, user, pass, status) VALUES ('".$param['nombre']."','".$param['usr']."','".$pass."','activo')";
            $sql = $dbConn->prepare($consulta);
            $sql->execute();
            $postId = $dbConn->lastInsertId();
            $response = array(
                "exito" => "usuario agregado con exito",
            );
            echo json_encode($response);
        }  
    }
    function uptate($param){
        include ("coneccion.php");
        include ("conect.php");
        $dbConn =  connect($db);
        
        $parametros="";
        if (isset($param['usr']) && $param['usr']!='') {
            if (isset($param['nombre']) & $param['nombre']!='') {
                $parametros="nombre='".$param['nombre']."'";
            }
            if (isset($param['usrNuevo']) && $param['usrNuevo']!='') {
                if ($parametros!='') {
                    $parametros.=", ";    
                }
                $parametros.="user='".$param['usrNuevo']."'";
            }
            if (isset($param['password']) && $param['password']!='') {
                $pass=md5($param['password']);
                if ($parametros!='') {
                    $parametros.=", ";    
                }
                $parametros.="pass='".$param['password']."'";
            }
            if (isset($param['status']) && $param['status']!='') {
                if ($parametros!='') {
                    $parametros.=", ";    
                }
                $parametros.="status='".$param['status']."'";
            }
            if ($parametros!='') {
                $consulta="UPDATE usuarios SET $parametros where user='".$param['usr']."'";
                $sql = $dbConn->prepare($consulta);
                $sql->execute();
                $response = array(
                    "exito" => "fue actualizado con exito",
                );
                echo json_encode($response);
            }else {
                $response = array(
                    "err" => "no se agregaron datos",
                );
                echo json_encode($response);
            }
        }else {
            $response = array(
                "err" => "no se a ingresado nombre del usuario",
            );
            echo json_encode($response);
        }
    }
    
    function delete($param){
        include ("coneccion.php");
        include ("conect.php");
        $dbConn =  connect($db);
        if (isset($param['usrDelete']) && $param['usrDelete']!='') {
            
            $consulta="UPDATE usuarios SET status='inactivo' where user='".$param['usrDelete']."'";
            $sql = $dbConn->prepare($consulta);
            $sql->execute();
            $response = array(
                "exito" => "fue eliminado con exito",
            );
            echo $consulta;

        }else {
            $response = array(
                "err" => "no se a ingresado nombre del usuario a eliminar",
            );
            echo json_encode($response);
        }
    }
?>