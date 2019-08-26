<?php
require_once 'includes/connect.php';

$sql = "CREATE TABLE IF NOT EXISTS users(
			user_id  int(255) auto_increment not null,
			identificacion	 varchar(50),
			nombres varchar(255),
			apellidos   varchar(255),
			direccion  varchar(255),
			instrumento	  varchar(20),
      acudiente	   varchar(255),
      telacud   varchar(255),
			image	   varchar(255),
			password   varchar(255),
			CONSTRAINT pk_users PRIMARY KEY(user_id)
		);";
    $create_usuarios_table = mysqli_query($db, $sql);
    if($create_usuarios_table){
    	echo "La tabla users se ha creado correctamente !!";
    }
    ?>
