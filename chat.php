<?php 
session_start();
ob_start(); // Limpiar o actualizar la salida para evitar errores de redireccionamiento
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
</head>
<body>
    
<h2>Chat</h2>

<!-- Imprimir el nombre del usuario que se está uniendo -->
<p>Bienvenido: <span id="nombre-usuario"><?php echo $_SESSION['usuario']; ?></span></p>

<!-- Campo para que el usuario escriba el nuevo mensaje -->
<label>Nuevo Mensaje:</label>
<input type="text" name="mensaje" id="mensaje" placeholder="Escribe un mensaje..."><br><br>

<input type="button" onclick="enviar()" value="Enviar"><br><br>

<!-- Recibir los mensajes enviados desde el chat en JavaScript -->
<span id="mensaje-chat"></span>

<script>

    // Recuperar el elemento donde se mostrarán los mensajes del chat
    const mensajeChat = document.getElementById("mensaje-chat");

    // URL de conexión del WebSocket
    const ws = new WebSocket('ws://localhost:8080');

    // Establecer la conexión del WebSocket
    ws.onopen = (e) => {
        console.log('Conectado al WebSocket');
    }

    // Recibir un mensaje del WebSocket
    ws.onmessage = (mensajeRecibido) => {
        // Parsear el mensaje recibido
        let resultado = JSON.parse(mensajeRecibido.data);

        // Agregar el mensaje al área del chat
        mensajeChat.insertAdjacentHTML('beforeend', `${resultado.mensaje} `);

        // Mostrar una notificación para los nuevos mensajes
        notifyNewMessage(resultado.mensaje);
    }

    const enviar = () => {
        // Recuperar el campo de entrada del mensaje
        let mensaje = document.getElementById("mensaje");

        // Recuperar el nombre del usuario
        let nombreUsuario = document.getElementById("nombre-usuario").textContent;
                   
        let datos = {
            mensaje: `${nombreUsuario}: ${mensaje.value}<br>` 
        }
        // Enviar un mensaje a través del WebSocket
        ws.send(JSON.stringify(datos));

        // Agregar el mensaje al área del chat
        mensajeChat.insertAdjacentHTML('beforeend', `${nombreUsuario}: ${mensaje.value} <br>`); 

        // Limpiar el campo de entrada del mensaje
        mensaje.value = '';
    }

    // Función para mostrar una notificación para los nuevos mensajes
function notifyNewMessage(message) {
    // Parsear el mensaje recibido para extraer el nombre de usuario y el contenido del mensaje
    let parts = message.split(':');
    let usuario = parts[0];
    let mensaje = parts[1];

    // Construir el mensaje para la notificación
    let notificationMessage = usuario + ' te ha enviado un mensaje:\n' +  mensaje;

    if (Notification.permission === "granted") {
        var notification = new Notification("Nuevo Mensaje", {
            body: notificationMessage // Mostrar el mensaje personalizado en la notificación
        });
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(function (permission) {
            if (permission === "granted") {
                var notification = new Notification("Nuevo Mensaje", {
                    body: notificationMessage // Mostrar el mensaje personalizado en la notificación
                });
            }
        });
    }
}

</script>

</body>
</html>
