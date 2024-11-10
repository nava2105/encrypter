<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $encrypted_file = $_FILES['encrypted_file'];
    $decrypt_key = $_POST['decrypt_key'];

    // Validar que el archivo es un PDF encriptado
    if ($encrypted_file['type'] !== 'application/pdf') {
        die("El archivo debe ser un PDF.");
    }

    $encrypted_data = file_get_contents($encrypted_file['tmp_name']);
    $encrypted_data = base64_decode($encrypted_data);

    // Extraer IV y datos encriptados
    $iv_length = openssl_cipher_iv_length('aes-256-cbc');
    $iv = substr($encrypted_data, 0, $iv_length);
    $encrypted = substr($encrypted_data, $iv_length);

    $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $decrypt_key, 0, $iv);

    // Crear un archivo temporal para la descarga
    $temp_file = 'decrypted_' . $encrypted_file['name'];
    file_put_contents($temp_file, $decrypted);

    // Forzar la descarga del archivo desencriptado
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