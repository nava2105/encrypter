<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $file = $_FILES['file'];
    $key = $_POST['key'] ?: bin2hex(random_bytes(16)); // Generar una clave aleatoria si no se proporciona

    // Validar que el archivo es un PDF
    if ($file['type'] !== 'application/pdf') {
        die("El archivo debe ser un PDF.");
    }

    $data = file_get_contents($file['tmp_name']);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    $encrypted_file = base64_encode($iv . $encrypted);

    // Crear un archivo temporal para la descarga
    $temp_file = 'encrypted_' . $file['name'];
    file_put_contents($temp_file, $encrypted_file);

    // Forzar la descarga del archivo encriptado
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($temp_file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($temp_file));
    readfile($temp_file);

    // Eliminar el archivo temporal después de la descarga
    unlink($temp_file);
    exit();
}
?>