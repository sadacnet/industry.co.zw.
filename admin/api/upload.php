<?php
/**
 * Admin API: File Upload
 * POST /admin/api/upload.php
 * Handles logo, banner, flyer, poster, document, gallery uploads
 */

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../includes/auth-check.php';
requireAdminLogin();

try {
    // Check if file was uploaded
    if (!isset($_FILES['file'])) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "No file uploaded"]);
        exit;
    }

    $file = $_FILES['file'];
    $type = isset($_POST['type']) ? $_POST['type'] : 'gallery';
    
    // Validate file type
    $validTypes = ['logo', 'banner', 'flyer', 'poster', 'document', 'gallery'];
    if (!in_array($type, $validTypes)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid upload type. Valid: " . implode(', ', $validTypes)]);
        exit;
    }

    // Use absolute path for upload directory
    $projectRoot = dirname(__DIR__, 2) . '/';
    $uploadDir = $projectRoot . 'uploads/' . $type . 's/';

    // Debug: Uncomment to see the path (remove in production)
    // echo json_encode(["debug" => $uploadDir]);
    // exit;

    // Create directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Failed to create upload directory: " . $uploadDir]);
            exit;
        }
    }

    // Validate file
    $fileName = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    // Check for upload errors
    if ($fileError !== 0) {
        $errorMessages = [
            1 => 'File exceeds upload_max_filesize',
            2 => 'File exceeds MAX_FILE_SIZE',
            3 => 'File only partially uploaded',
            4 => 'No file uploaded',
            6 => 'Missing temporary folder',
            7 => 'Failed to write file to disk',
            8 => 'PHP extension stopped upload'
        ];
        $errorMsg = isset($errorMessages[$fileError]) ? $errorMessages[$fileError] : "Unknown error code: $fileError";
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => $errorMsg]);
        exit;
    }

    // Check file size (max 10MB)
    if ($fileSize > 10000000) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "File too large. Maximum size is 10MB"]);
        exit;
    }

    // Get file extension
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Allowed extensions
    $allowedExts = [
        'logo' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'banner' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'flyer' => ['pdf', 'jpg', 'jpeg', 'png'],
        'poster' => ['jpg', 'jpeg', 'png', 'webp'],
        'document' => ['pdf', 'doc', 'docx'],
        'gallery' => ['jpg', 'jpeg', 'png', 'gif', 'webp']
    ];

    if (!in_array($fileExt, $allowedExts[$type])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Invalid file type '{$fileExt}'. Allowed: " . implode(', ', $allowedExts[$type])
        ]);
        exit;
    }

    // Generate unique filename
    $newFileName = $type . '_' . uniqid() . '_' . time() . '.' . $fileExt;
    $uploadPath = $uploadDir . $newFileName;

    // Move uploaded file
    if (move_uploaded_file($fileTmp, $uploadPath)) {
        // Return the file path relative to project root
        $relativePath = 'uploads/' . $type . 's/' . $newFileName;
        
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "File uploaded successfully!",
            "data" => [
                "file_path" => $relativePath,
                "full_url" => (defined('SITE_ROOT') ? SITE_ROOT : '') . '/' . $relativePath,
                "file_name" => $newFileName,
                "original_name" => $fileName,
                "file_type" => $fileExt,
                "file_size" => $fileSize
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error", 
            "message" => "Failed to save file. Upload dir: " . $uploadDir . " | Tmp: " . $fileTmp
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Upload failed: " . $e->getMessage()]);
}
?>