<?php 

// Подключение к БД
$pdo = new PDO('mysql:host=localhost;dbname=personal_website;charset=utf8', 'root', null, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);

// Данные которые передали через Authorization
$headers = getallheaders();
$token = isset($headers['Authorization']) ? $headers['Authorization'] : null;

// Переменная, в которую необходимо будет записать пользователя из БД
$user = [];

// Задание: по токену ($token) найти пользователя в БД и записать в переменную $user
if ($token) {
    // Для простоты предположим, что токен - это просто ID пользователя.
    $stmt = $pdo->prepare("SELECT first_name, last_name, phone, document_number FROM users WHERE api_token = :token");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();
}
// Условие выполнится, если переменная $user не пустая
if (!empty($user)) {
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($user);
} else {
    // Условие выполнится, если переменная $user пустая
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode([
        "error" => [
            "code" => 401,
            "message" => "Unauthorized"
        ]
    ]);
}

?>