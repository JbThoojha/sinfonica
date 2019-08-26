
<?php require_once 'includes/header.php';?>
<?php
function mostrarError($error, $field){
  if(isset($error[$field]) && !empty($field)){
    $alerta='<div class="alert alert-danger">'.$error[$field].'</div>';
  }else{
    $alerta='';
  }
  return $alerta;
}
function setValueField($error,$field, $textarea=false){
  if(isset($error) && count($error)>=1 && isset($_POST[$field])){
    if($textarea != false){
      echo $_POST[$field];
    }else{
      echo "value='{$_POST[$field]}'";
    }
  }
}
$error=array();
if(isset($_POST["submit"])){
  if(!empty($_POST["identificacion"])){
    $identificacion_validador=true;
   }else{
   $identificacion_validador=false;
    $error["identificacion"]="Digite su numero de identificacion";
     }
 if(!empty($_POST["nombres"]) && strlen($_POST["nombres"]<=20)  && !preg_match("/[0-9]/", $_POST["nombres"])){
$nombre_validador=true;
}else{
$nombre_validador=false;
$error["nombres"]="El nombre no es válido";
}
  if(!empty($_POST["apellidos"])&& !is_numeric($_POST["apellidos"]) && !preg_match("/[0-9]/", $_POST["apellidos"])){
      $apellidos_validador=true;
     }else{
     $apellidos_validador=false;
       $error["apellidos"]="Los apellidos no son válidos";
        }
     if(!empty($_POST["identificacion"]) && is_numeric($_POST["identificacion"]) && strlen($_POST["identificacion"]>=10 )){
       $identificacion_validador=true;
      }else{
      $identificacion_validador=false;
       $error["identificacion"]="Digite su numero de identificacion";
        }

     if(!empty($_POST["direccion"])){
       $direccion_validador=true;
      }else{
      $direccion_validador=false;
       $error["direccion"]="Introduzca la direecion de residencia";
        }
        if(!empty($_POST["acudiente"])&& !is_numeric($_POST["acudiente"]) && !preg_match("/[0-9]/", $_POST["acudiente"])){
            $acudiente_validador=true;
           }else{
           $acudiente_validador=false;
             $error["acudiente"]="Los nombres del acudiente no son válidos";
              }
          if(!empty($_POST["telacud"])){
                $telacud_validador=true;
               }else{
               $telacud_validador=false;
                $error["telacud"]="Introduzca telefono del acudiente";
                 }
     if(isset($_POST["instrumento"]) && is_numeric($_POST["instrumento"])){
       $instrumento_validador=true;
      }else{
      $instrumento_validador=false;
       $error["instrumento"]="Seleccione un instrumento de usuario";
        }
      
      //Crear una carpeta nuevo código
      $image=null;
      if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])){
        if(!is_dir("uploads")){
          $dir = mkdir("uploads", 0777, true);
        }else{
          $dir=true;
        }
        if($dir){
          $filename= time()."-".$_FILES["image"]["name"]; //concatenar función tiempo con el nombre de imagen
          $muf=move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/".$filename); //mover el fichero utilizando esta función
          $image=$filename;
          if($muf){
            $image_upload=true;
          }else{
            $image_upload=false;
            $error["image"]= "La imagen no se ha subido";
          }
        }
        //var_dump($_FILES["image"]);
        //die();
	 	}
    //Insertar Usuarios en la base de Datos
    if(count($error)==0){

    $sql= "INSERT INTO users VALUES(NULL, '{$_POST["identificacion"]}', '{$_POST["nombres"]}', '{$_POST["apellidos"]}', '{$_POST["direccion"]}', '{$_POST["instrumento"]}', '{$_POST["acudiente"]}', '{$_POST["telacud"]}','{$image}', '".sha1($_POST["password"])."');";

      $insert_user=mysqli_query($db, $sql);
    }else{
      $insert_user=false;
    }
}
?>
<h1>Crear Usuarios</h1>
<?php if(isset($_POST["submit"]) && count($error)==0 && $insert_user !=false){?>
  <div class="alert alert-success">
    El usuario se ha creado correctamente !!
  </div>
<?php } ?>
<form action="crear.php" method="POST" enctype="multipart/form-data">
  <label for="identificacion">No. Identificacion:
  <input type="text" name="identificacion" class="form-control" <?php setValueField($error, "identificacion");?>/>
  <?php echo mostrarError($error, "identificacion");?>
  </label>
  </br></br>
    <label for="nombre">Nombres:
    <input type="text" name="nombres" class="form-control" <?php setValueField($error, "nombres");?>/>
    <?php echo mostrarError($error, "nombres");?>
    </label>
    </br></br>
    <label for="apellidos">Apellidos:
        <input type="text" name="apellidos" class="form-control" <?php setValueField($error, "apellidos");?>/>
        <?php echo mostrarError($error, "apellidos");?>
    </label>
    </br></br>
    <label for="instrumento" class="form-control">Instrumento:
        <select name="instrumento">
        <option value="0">Escoja instrumento</option>
            <option value="1">Trompeta</option>
            <option value="2">Clarinete</option>
            <option value="3">Saxofon</option>
            <option value="4">Oboe</option>
            <option value="5">Violin</option>
            <option value="6">Bombo</option>
            <option value="7">Corno frances</option>
        </select>
        <?php echo mostrarError($error, "instrumento");?>
    </label>
    </br></br>
    <label for="direccion">Direccion:
        <input type="text" name="direccion" class="form-control" <?php setValueField($error, "direccion");?>/>
        <?php echo mostrarError($error, "direccion");?>
    </label>
    </br></br>
    <label for="acudiente">Nombre del acudiente:
        <input type="text" name="acudiente" class="form-control" <?php setValueField($error, "acudiente");?>/>
        <?php echo mostrarError($error, "acudiente");?>
    </label>
    </br></br>
    <label for="telacud">Telefono del acudiente:
        <input type="tel" name="telacud" class="form-control" <?php setValueField($error, "telacud");?>/>
        <?php echo mostrarError($error, "telacud");?>
    </label>
    </br></br>
    <label for="image">Imagen:
        <input type="file" name="image" class="form-control"/>
    </label>
    </br></br>
    <label for="password">Contraseña:
         <input type="password" name="password" class="form-control"/>
         <?php echo mostrarError($error, "password");?>
     </label>
     </br></br>
    <input type="submit" value="Enviar" name="submit" class="btn btn-success"/>
</form>
<?php require_once 'includes/footer.php'; ?>
