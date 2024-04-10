<?php

    session_start();
    
    ob_start(); //limpiar o actualizar la salida para evitar error de redicionamiento

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Acceder al chat</h1>

    <?php

    //accede o si cuando el usuario le da click en el boton de acceder al formulario
    $datos= filter_input_array(INPUT_POST,FILTER_DEFAULT);

    if(!empty($datos['acceder'])){ 

        //  es para mirar el string y lo q le entra la info al acceder el nombre var_dump($datos);

        //crea una sesion o atribute el nombre del usuario
        $_SESSION['usuario'] = $datos['usuario'];

        //redirecciona al chat
        header("Location: chat.php");


    }
    ?>
    <form method="POST" action="">
        <label>Nombre: </label>
        <input type="text" name="usuario" placeholder="Digite un nombre" ><br><br>

        <input type="submit" name="acceder" value="acceder"> <br><br>
    </form>
</body>
</html>