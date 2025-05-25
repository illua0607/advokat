<?php
$result = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $context = $_POST['context'] ?? '';
    $claim = $_POST['claim'] ?? '';

    // Чтение всех .txt файлов из /laws
    $laws = '';
    foreach (glob(__DIR__ . '/laws/*.txt') as $file) {
        $laws .= file_get_contents($file) . "\n\n";
    }

    // Готовим промпт
    $prompt = "Ты — юридический помощник. Ниже суть дела, иск и законы. Ответь, чем можно защищаться, на какие статьи сослаться, и как себя вести в суде.

Мои пояснения:\n$context\n\nИск:\n$claim\n\nЗАКОНЫ:\n$laws";

    // Отправляем в ChatGPT
    $response = file_get_contents("chatgpt.php?prompt=" . urlencode($prompt));
    $result = $response ?: 'Ошибка запроса';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Юридический ИИ</title>
    <style>
        body { font-family: sans-serif; padding: 30px; }
        textarea { width: 100%; height: 200px; margin-bottom: 20px; }
        .result { background: #f4f4f4; padding: 20px; white-space: pre-wrap; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <h1>ИИ-Помощник по искам</h1>
    <form method="post">
        <label><b>Суть и мои высказывания:</b></label><br>
        <textarea name="context" required><?= htmlspecialchars($_POST['context'] ?? '') ?></textarea><br>

        <label><b>Текст иска:</b></label><br>
        <textarea name="claim" required><?= htmlspecialchars($_POST['claim'] ?? '') ?></textarea><br>

        <button type="submit">Анализировать</button>
    </form>

    <?php if ($result): ?>
        <h2>🔍 Ответ ChatGPT:</h2>
        <div class="result"><?= htmlspecialchars($result) ?></div>
    <?php endif; ?>
</body>
</html>
