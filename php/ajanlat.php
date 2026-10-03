<?php

declare(strict_types=1);

require __DIR__ . '/../includes/db.php';

const SERVICES = ['home_wiring', 'fault_repair', 'safety_inspection', 'solar', 'ev_charger', 'smart_home', 'other'];
const PROPERTY_TYPES = ['flat', 'house', 'business'];
const SUCCESS_MESSAGE = 'Köszönjük az ajánlatkérést! Hétköznap két órán belül telefonon keressük.';

function respond(int $status, array $body): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($body, JSON_UNESCAPED_UNICODE);
    exit;
}

function read_text(string $key): string
{
    $value = $_POST[$key] ?? '';

    return is_string($value) ? trim($value) : '';
}

function normalize_phone(string $phone): string
{
    return preg_replace('/[\s\-\/()]/', '', $phone);
}

function is_length_between(string $value, int $min, int $max): bool
{
    $length = mb_strlen($value);

    return $length >= $min && $length <= $max;
}

function validate(array $data, bool $consent): array
{
    $errors = [];

    if (!is_length_between($data['name'], 2, 80)) {
        $errors['name'] = 'A név 2 és 80 karakter közötti legyen.';
    }
    if (!is_length_between($data['email'], 5, 120) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Adjon meg érvényes e-mail-címet.';
    }
    if (!preg_match('/^(\+36|06)\d{8,9}$/', $data['phone'])) {
        $errors['phone'] = 'Adjon meg magyar telefonszámot, például +36 30 123 4567.';
    }
    if (!is_length_between($data['city'], 2, 60)) {
        $errors['city'] = 'A település neve 2 és 60 karakter közötti legyen.';
    }
    if (!in_array($data['service'], SERVICES, true)) {
        $errors['service'] = 'Válasszon szolgáltatást a listából.';
    }
    if (!in_array($data['property_type'], PROPERTY_TYPES, true)) {
        $errors['property_type'] = 'Válassza ki az ingatlan típusát.';
    }
    if (!is_length_between($data['description'], 10, 1000)) {
        $errors['description'] = 'A leírás 10 és 1000 karakter közötti legyen.';
    }
    if (!$consent) {
        $errors['consent'] = 'Az adatkezelési hozzájárulás nélkül nem tudjuk fogadni a kérést.';
    }

    return $errors;
}

function save_request(PDO $pdo, array $data): void
{
    $statement = $pdo->prepare(
        'INSERT INTO quote_requests (name, email, phone, city, service, property_type, description, urgent)
         VALUES (:name, :email, :phone, :city, :service, :property_type, :description, :urgent)'
    );
    $statement->execute($data);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, ['ok' => false, 'message' => 'Ez a végpont csak POST kéréseket fogad.']);
}

// A rejtett "website" mezőt csak robotok töltik ki; nekik is sikert mutatunk, de nem mentünk.
if (read_text('website') !== '') {
    respond(200, ['ok' => true, 'message' => SUCCESS_MESSAGE]);
}

$data = [
    'name' => read_text('name'),
    'email' => read_text('email'),
    'phone' => normalize_phone(read_text('phone')),
    'city' => read_text('city'),
    'service' => read_text('service'),
    'property_type' => read_text('property_type'),
    'description' => read_text('description'),
    'urgent' => isset($_POST['urgent']) ? 1 : 0,
];

$errors = validate($data, isset($_POST['consent']));
if ($errors) {
    respond(422, [
        'ok' => false,
        'message' => 'Kérjük, ellenőrizze a megadott adatokat.',
        'errors' => $errors,
    ]);
}

try {
    save_request(connect_database(), $data);
} catch (PDOException $exception) {
    // Csak a hibakód kerül a naplóba: az üzenet a hostot és a felhasználónevet is tartalmazhatja.
    error_log('Az ajánlatkérés mentése sikertelen, hibakód: ' . $exception->getCode());
    respond(500, [
        'ok' => false,
        'message' => 'Technikai hiba miatt nem tudtuk rögzíteni az ajánlatkérést. Kérjük, hívjon minket telefonon.',
    ]);
}

respond(200, ['ok' => true, 'message' => SUCCESS_MESSAGE]);
