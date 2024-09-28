<?php

$OPENAI_API_KEY = "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";
$sModel = "gpt-3.5-turbo-0613";

// Función para enviar la solicitud a la API de OpenAI
function enviarSolicitudAPI($messages) {
    global $OPENAI_API_KEY, $sModel;

    $ch = curl_init();
    $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
        'Authorization: Bearer ' . $OPENAI_API_KEY,
    ];

    curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['model' => $sModel, 'messages' => $messages]));

    $resultado = curl_exec($ch);
    $decoded_json = json_decode($resultado, true);

    if (isset($decoded_json['choices'][0]['message']['content'])) {
        return $decoded_json['choices'][0]['message']['content'];
    } else {
        echo "Error al procesar la respuesta de la API: " . json_encode($decoded_json);
        return "Error al procesar la respuesta de la API.";
    }
}

// Obtener el mensaje del usuario
$userMessage = isset($_POST['text']) ? $_POST['text'] : '';
$username = isset($_POST['username']) ? $_POST['username'] : '';
$grade = isset($_POST['grade']) ? $_POST['grade'] : '';

// Construir el array de mensajes
$messages = [
    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
    ['role' => 'user', 'content' => $userMessage],
    ['role' => 'user', 'content' => "Username: $username"],
    ['role' => 'user', 'content' => "Grade: $grade"],
];

// Enviar el array de mensajes a la API y obtener la respuesta del chatbot
$respuestaChatbot = enviarSolicitudAPI($messages);

// Mostrar la respuesta del chatbot
echo $respuestaChatbot;
?>
