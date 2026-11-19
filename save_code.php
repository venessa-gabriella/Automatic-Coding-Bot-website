
<?php
session_start();
header("Content-Type: application/json");

include "config/db.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Please login first."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$name = trim($data["project_name"] ?? "");
$language = $data["language"] ?? "";
$type = $data["project_type"] ?? "";
$description = trim($data["description"] ?? "");
$code = $data["generated_code"] ?? "";

if ($name == "" || $code == "" ||
    !in_array($language, ["PHP", "HTML", "JavaScript"]) ||
    !in_array($type, [
        "Student Management",
        "Library Management",
        "Employee Management",
        "Inventory Management",
        "Login System"
    ])) {

    http_response_code(400);
    echo json_encode(["error" => "Invalid project information."]);
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO projects
    (user_id, project_name, language, project_type, description, generated_code)
    VALUES (?, ?, ?, ?, ?, ?)"
);

$stmt->bind_param(
    "isssss",
    $_SESSION["user_id"],
    $name,
    $language,
    $type,
    $description,
    $code
);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Project saved successfully."
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to save project."]);
}
?>
