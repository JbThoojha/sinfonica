<?php require_once("includes/header.php")?>
<?php
function mostrarError($error, $field){
  if(isset($error[$field]) && !empty($field)){
    $alerta='<div class="alert alert-danger">'.$error[$field].'</div>';
  }else{
    $alerta='';
  }
  return $alerta;
}
function setValueField($datos, $field, $textarea=false){
  if(isset($datos) && count($datos)>=1){
    if($textarea != false){
      echo $datos[$field];
    }else{
      echo "value='{$datos[$field]}'";
    }
  }
}
//Buscar Usuario
if(!isset($_GET["id"]) || empty($_GET["id"]) || !is_numeric($_GET["id"])){
  header("location:index.php");
  }
$id=$_GET["id"];
$user_query=mysqli_query($db, "SELECT * FROM users WHERE user_id={$id}");
$user=mysqli_fetch_assoc($user_query);
if(!isset($user["user_id"]) || empty($user["user_id"])){
  header("location:index.php");
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
        if(!empty($_POST["password"]) && strlen($_POST["password"]>=6)){
       $email_validador=true;
      }else{
      $email_validador=false;
       $error["password"]="Introduzca una contraseña de más de seis caracteres";
        }

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
    if(count($error)==0){
      $sql= "UPDATE users set identificacion='{$_POST["identificacion"]}',"
      . "nombres='{$_POST["nombres"]}',"
      . "apellidos= '{$_POST["apellidos"]}',"
      . "direccion= '{$_POST["direccion"]}',"
      . "instrumento= '{$_POST["instrumento"]}',"
      . "acudiente= '{$_POST["acudiente"]}',"
      . "telacud= '{$_POST["telaacud"]}'";
      if(isset($_POST["password"]) && !empty($_POST["password"])){
        $sql.= "password='".sha1($_POST["password"])."', ";
     }
     //Código nuevo
    if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])){
      $sql.= "image='{$image}', ";
   }
     $sql.= "instrumento= '{$_POST["instrumento"]}'WHERE user_id={$user["user_id"]};";
     $update_user=mysqli_query($db, $sql);
     if($update_user){
       $user_query=mysqli_query($db, "SELECT * FROM users WHERE user_id={$id}");
       $user=mysqli_fetch_assoc($user_query);
     }
   }else{
     $update_user=false;
   }
}
?>
<h2>Editar Usuario <?php echo $user["user_id"]."-".$user["nombres"]."-".$user["apellidos"];?></h2>
<?php if(isset($_POST["submit"]) && count($error)==0 && $update_user !=false){?>
  <div class="alert alert-success">
    El usuario se ha actualizado correctamente !!
  </div>
<?php }elseif(isset($_POST["submit"])){?>
  <div class="alert alert-danger">
    El usuario NO se ha actualizado correctamente !!
  </div>
<?php } ?>
<form action="" method="POST" enctype="multipart/form-data">
    <label for="nombres">Nombre:
    <input type="text" name="nombres" class="form-control" <?php setValueField($user, "nombres");?>/>
    <?php echo mostrarError($error, "nombres");?>
    </label>
    </br></br>
    <label for="apellidos">Apellidos:
        <input type="text" name="apellidos" class="form-control" <?php setValueField($user, "apellidos");?>/>
        <?php echo mostrarError($error, "apellidos");?>
    </label>
    </br></br>
    <label for="bio">Biografia:
        <textarea name="bio" class="form-control"><?php setValueField($user, "bio", true);?></textarea>
        <?php echo mostrarError($error, "bio");?>
    </label>
    </br></br>

    <label for="image">
      <?php if($user["image"] != null){?>
        Imagen de Perfil: <img src="uploads/<?php echo $user["image"] ?>" width="100"/><br/>
      <?php } ?>
        Actualizar Imagen de Perfil:
        <input type="file" name="image" class="form-control"/>
        <!--Nuevo Código-->

    </label>
    </br></br>
    <label for="password">Contraseña:
        <input type="password" name="password" class="form-control"/>
        <?php echo mostrarError($error, "password");?>
    </label>
    </br></br>
  
    </br></br>
    <input type="submit" value="Enviar" name="submit" class="btn btn-success"/>
</form>
<?php require_once("includes/footer.php")?>
