<?php
$prompt = $_GET['prompt'] ?? '';

$api_key = 'sk-ваш_ключ_сюда'; // <-- сюда вставь свой OpenAI API-ключ

$data = [
    "model" => "gpt-3.5-turbo",
    "messages" => [
        ["role" => "system", "content" => "Ты — юридический помощник. Отвечай по существу, ссылками на статьи законов."],
        ["role" => "user", "content" => $prompt]
    ]
];

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $api_key"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Ошибка: ' . curl_error($ch);
    exit;
}
curl_close($ch);

$json = json_decode($response, true);
echo $json['choices'][0]['message']['content'] ?? 'Пустой ответ';
