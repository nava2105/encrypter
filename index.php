<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encrypt and Decrypt PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1>Encrypt and Decrypt PDF files</h1>

    <form id="encryptForm" action="encrypt.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="file">Select a PDF file to encrypt:</label>
            <input type="file" class="form-control-file" id="file" name="file" accept=".pdf" required>
        </div>
        <div class="form-group">
            <label for="key">Enter the key (optional, leave blank to generate a random one):</label>
            <input type="text" class="form-control" id="key" name="key">
        </div>
        <button type="button" class="btn btn-primary" id="showModalButton">Encrypt</button>
    </form>

    <!-- Modal to show the key -->
    <div class="modal fade" id="keyModal" tabindex="-1" role="dialog" aria-labelledby="keyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="keyModalLabel">Encryption key</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Your encryption key is:</p>
                    <input type="text" id="generatedKey" class="form-control" readonly>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="confirmEncrypt">Accept and Encrypt</button>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <form action="decrypt.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="encrypted_file">Select a encrypted PDF file to decrypt:</label>
            <input type="file" class="form-control-file" id="encrypted_file" name="encrypted_file" accept=".pdf" required>
        </div>
        <div class="form-group">
            <label for="decrypt_key">Enter the key to decrypt:</label>
            <input type="text" class="form-control" id="decrypt_key" name="decrypt_key" required>
        </div>
        <button type="submit" class="btn btn-danger">Decrypt</button>
    </form>
</div>

<script>
    document.getElementById('showModalButton').onclick = function() {
        const keyInput = document.getElementById('key').value;
        let generatedKey;

        if (!keyInput) {
            generatedKey = Math.random().toString(36).slice(-8);
        } else {
            generatedKey = keyInput;
        }
        document.getElementById('generatedKey').value = generatedKey;
        $('#keyModal').modal('show');
    };

    document.getElementById('confirmEncrypt').onclick = function() {
        const form = document.getElementById('encryptForm');
        const keyField = document.createElement('input');
        keyField.type = 'hidden';
        keyField.name = 'key';
        keyField.value = document.getElementById('generatedKey').value;

        form.appendChild(keyField);

        form.submit();
    };
</script>
</body>
</html>