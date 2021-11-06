<!DOCTYPE html>
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Emerald 
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20120902

-->
<html>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Laboratorio Programaci&oacute;n III</title>
	<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
	<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="styletabla.css" rel="stylesheet" type="text/css" media="screen" />
	<style>
	.error {color: #FF0000;}
	</style>
</head>

<body>
<?php function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>


	<div id="wrapper">
		<div id="header-wrapper" class="container">
			<div id="header" class="container">
				<div id="logo">
					<h1><a href="#">Usuarios</a></h1>
				</div>
				<div id="menu">
					<ul>
						<li class="current_page_item"><a href="index.php">Homepage</a></li>
						<li><a href="agregar.php">Nuevo</a></li>
						<li><a href="listar.php">Listar</a></li>
						<li><a href="borrar.php">eliminar</a></li>
					</ul>
				</div>
			</div>
			<div><img src="images/img03.png" width="1000" height="40" alt="" /></div>
		</div>
		<!-- end #header -->

		<div id="page">
			<div id="content">
				<div class="post">
					<h2>Sistema de Administraci&oacute;n de Usuarios</h2>
					<p class="meta"><span class="date"><?php echo date("d - m - Y"); ?></span></p>
					<div style="clear: both;">&nbsp;</div>
					<div class="entry">
						<h3>Nuevo Usuario</h3>
						<br>
						<?php 
$nameError=$apError=$mailError=$dirError=$telError=$genError=$nickError="";
$nombre=$apellido=$mail=$nick=$dire=$tel=$gen="";
$valido=TRUE;
$enviado=$_SERVER["REQUEST_METHOD"]=="POST";

if($enviado){
    $nombre=$_POST["nombre"];
    $apellido=$_POST["apellido"];
    $dire=$_POST["dire"];

    $tel=$_POST["tel"];
    $mail=$_POST["mail"];
    $nick=$_POST["nick"];
	

	if(empty($nombre)){ 
        $nameError= "*El nombre es requerido."; 
    }else{
            $nombre = test_input($_POST["nombre"]);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z ]*$/",$nombre)) {
              $nameError = "*Formato inválido. Sólo letras."; 
            }else{
				if(strlen($nombre)>30){
					$nameError = "*El nombre es demasiado largo.";
				}
				
			}
          
    }

    if(empty($apellido)){
		 $apError= "*El apellido es requerido.";
	}else{
		$apellido= test_input($_POST["apellido"]);
		if (!preg_match("/^[a-zA-Z ]*$/",$apellido)) {
			$apError = "*Formato inválido. Sólo letras."; 
		  }else{
			if(strlen($apellido)>30){
				$apError = "*El apellido es demasiado largo.";
			}
			
		}
	}

    if(empty($dire)){
		 $dirError= "*La dirección es requerida.";
	}else{
		if(strlen($dire)>200){
			$dirError = "*La dirección es demasiado larga.";
		}
		
	}
    if(empty($tel)){
		 $telError= "*El número de teléfono es requerido.";
	}else{
		if(strlen($tel)<10 || strlen($tel)>12   || !is_numeric($tel)){
			$telError = "*El número de teléfono es inválido.";
		}
		
	}
    if(empty($_POST["gen"])){
		 $genError= "*El género es requerido.";
	}else{
		$gen=$_POST["gen"];
	}


    if(empty($mail)) $mailError= "*El mail es requerido.";
    if(empty($nick)){
		 $nickError= "*El nick es requerido.";
	}else{
		if(strlen($nick)>20){
			$nickError = "*El nick es demasiado largo.";
		}else{
			if(strlen($nick)<5){
				$nickError = "*El nick es demasiado corto.";

			}
		}
		
	}

	

    
    // // Se conecta a la base de datos
    $dbconn = pg_connect("host=localhost dbname=usuariosdb user=danielam password=19demayo")
        or die('No se ha podido conectar: ' . pg_last_error());
    // // Se busca un usuario con el nick ingresado en el formulario
     $result = pg_query_params($dbconn, 'SELECT * FROM usuarios.usuario WHERE nick = $1', array($nick));
    if ($line = pg_fetch_assoc($result)) {
        if (count($line) > 0) {
            $nickError = "*El nick ya existe";
            $error = true;
     }
     }
    


    $valido= (empty($nameError) && empty($apError) && empty($dirError) && empty($telError) && empty($genError) && empty($mailError) && empty($nickError) );
}?>

<?php if(($enviado && $valido)) {?>
    
    <?php
					$usuarios= fopen("usuarios.txt", "a");
					fputs($usuarios, $nombre." ");
					fputs($usuarios, $apellido." ");
					fputs($usuarios, $nick." ");
					fputs($usuarios, $mail." ");
					fputs($usuarios, $dire." ");
					fputs($usuarios, $tel." ");
					fputs($usuarios, $gen."\n");
					fclose($usuarios);

					 $array = array($nombre, $apellido, $nick, $mail, $dire, $tel, $gen);
					 $sql = 'INSERT INTO usuarios.usuario(nombre, apellido, nick, email, direccion, telefono, genero) values ($1, $2, $3, $4, $5, $6, $7);';
					// // Se inserta en la base de datos el nuevo usuario
					 $result = pg_query_params($dbconn, $sql, $array);
					?>
					<?php
					// // se cierra la conexión a la base de datos
					 if ($dbconn) {
						pg_close($dbconn);
					}
                    echo"<b> Usuario ". $nick. " creado</b><br><br>";
					$nameError=$apError=$mailError=$dirError=$telError=$genError=$nickError="";
					?>
					<a  href="agregar.php" ><h3><b>Cargar nuevo usuario</b></h3></a>
                

        <?php } else { ?>

    <form action="agregar.php" method="POST">
		Nombre:<br>
		<input type=text name=nombre value="<?php echo $nombre ?>">
		<span class="error"><?php  
         echo $nameError;?><br><br></span>
		Apellido:<br>
		<input type=text name=apellido value="<?php echo $apellido ?>">
		<span class="error"><?php  
         echo $apError;?><br><br></span>
        Dirección:<br>
		<input type=text name=dire value="<?php echo $dire ?>">
        <span class="error"><?php  
        echo $dirError;?><br><br></span>
        E-mail:<br>
		<input type=email name=mail value="<?php echo $mail ?>">
		<span class="error"><?php 
        echo $mailError;?><br><br></span>
		  Nick:<br>
		<input type=text name=nick value="<?php echo $nick ?>">
		<span class="error"><?php  
        echo $nickError;?><br><br></span>
		Telefono:<br>
		<input type=tel name=tel value="<?php echo $tel ?>">
		<span class="error"><?php 
        echo $telError;?><br><br></span>
		Género:
		<input type=radio name="gen"   value="Masculino" <?php if (  $gen=="Femenino") echo "checked";?> >Masculino
		<input type=radio name="gen"   value="Femenino" <?php if (  $gen=="Masculino") echo "checked";?>> Femenino
		<input type=radio name="gen"value="Otro"  <?php if (  $gen=="Otro") echo "checked";?>>Otro
		<span class="error"><?php  
        echo $genError;?><br><br></span>
        
		<input type=submit value="Enviar">
	</form>
	<?php }
    ?>






					</div>
				</div>
				<div style="clear: both;">&nbsp;</div>
			</div>
			<div style="clear: both;">&nbsp;</div>
		</div>
		<div class="container"><img src="images/img03.png" width="1000" height="40" alt="" /></div>
	</div>
	<div id="footer-content"></div>
	<div id="footer">
		<p>Copyright (c) 2012 Sitename.com. All rights reserved. Design by <a href="http://www.freecsstemplates.org/" rel="nofollow">FreeCSSTemplates.org</a>.</p>
	</div>
</body>

</html>