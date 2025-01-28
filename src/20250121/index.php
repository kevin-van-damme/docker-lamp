<?php
require('db.inc.php');

$errors = [];
$success = [];
$imgUpload = '';

if (isset($_POST['formSubmit'])) {
    if (isset($_FILES['imgUpload'])) {
        $errors     = array();
        $maxsize    = 1097152;
        $acceptable = array(
            'image/jpeg',
            'image/jpg',
            'image/png'
        );

        if (($_FILES['imgUpload']['size'] >= $maxsize)) {
            $errors[] = 'File too large. File must be less than 1 megabytes.';
        } else if (($_FILES["imgUpload"]["size"] == 0)) {
            $errors[] = 'Please upload a file...';
        }
        $file_mime = mime_content_type($_FILES['imgUpload']['tmp_name']);
        if (!in_array($file_mime, $acceptable)) {
            $errors[] = 'Invalid file type. Only JPG, JPEG and PNG types are accepted.';
        }

        if (count($errors) === 0) {
            $upload_dir = "uploads/";
            $upload_file = $upload_dir . basename($_FILES["imgUpload"]["tmp_name"]) . "." . basename($_FILES["imgUpload"]["type"]);

            if (move_uploaded_file($_FILES['imgUpload']['tmp_name'], $upload_file)) {
                echo "File is uploaded successfully!";
                insertDbImage($upload_file);
            } else {
                $errors[] = 'Error uploading file. Please try again.';
            }
        }
    }
}

$items = getDbImages();

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DB Images</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    <style>
        img.thumb {
            height: 50px;
        }
    </style>
</head>

<body>


    <div class="container">
        <section>
            <h2>Upload Image</h2>
            <hr />

            <?php if (count($errors)) : ?>
                <div class="alert alert-danger" role="alert">
                    <ul>
                        <?php foreach ($errors as $error) : ?>
                            <li><?= $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="index.php" enctype="multipart/form-data">

                <div class="form-group mt-3">
                    <label for="imgUpload" class="col-sm-2 col-form-label">Image: *</label>
                    <div>
                        <input type="file" id="imgUpload" name="imgUpload">
                    </div>
                </div>

                <div class="form-group mt-5">
                    <div>
                        <button type="submit" class="btn btn-primary" name="formSubmit" style="width: 100%">Add</button>
                    </div>
                </div>
            </form>


        </section>
        <main>


            <h2>Images</h2>
            <div class="table-responsive small">
                <table class="table table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Image</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($items as $item): ?>

                            <tr>
                                <td><?= $item['id']; ?></td>
                                <td><?= '<img src="' . $item['path'] . '" class="thumb"/>'; ?></td>
                                <td><?= $item['created_date']; ?></td>

                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>