<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$nama_kategori = $jumlah_hak = "";
$nama_kategori_err = $jumlah_hak_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_nama_kategori = trim($_POST["nama_kategori"]);
    if(empty($input_nama_kategori)){
        $nama_kategori_err = "silahkan masukkan nama.";
    } elseif(!filter_var($input_nama_kategori, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $nama_kategori_err = "silahkan masukkan nama yang benar.";
    } else{
        $nama_kategori = $input_nama_kategori;
    }

    // Validate address
    $input_jumlah_hak = trim($_POST["jumlah_hak"]);
    if(empty($input_jumlah_hak)){
        $jumlah_hak_err = "Silahkan masukkan jumlah Hak .";
    } else{
        $jumlah_hak = $input_jumlah_hak;
    }

    // Check input errors before inserting in database
    if(empty($nama_kategori_err) && empty($jumlah_hak_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO kategori_mustahik (nama_kategori, jumlah_hak) VALUES (?, ?)";

        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_nama_kategori, $param_jumlah_hak);

            // Set parameters
            $param_nama_kategori = $nama_kategori;
            $param_jumlah_hak = $jumlah_hak;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: kategori_mustahik.php");
                exit();
            } else{
                echo "ada yang salah. silahkan coba refresh.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Tambah Record</h2>
                    </div>
                    <p>Silahkan isi form di bawah ini kemudian submit untuk menambahkan data Kategori Mustahik ke dalam database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nama_kategori_err)) ? 'has-error' : ''; ?>">
                            <label>Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="form-control" value="<?php echo $nama_kategori; ?>">
                            <span class="help-block"><?php echo $nama_kategori_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($jumlah_hak_err)) ? 'has-error' : ''; ?>">
                            <label>Jumlah Hak</label>
                            <textarea name="jumlah_hak" class="form-control"><?php echo $jumlah_hak; ?></textarea>
                            <span class="help-block"><?php echo $jumlah_hak_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="kategori_mustahik.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>