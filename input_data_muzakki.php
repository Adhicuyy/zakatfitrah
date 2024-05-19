<?php

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$nama_muzakki = $jumlah_tanggungan = $keterangan = "";
$nama_muzakki_err = $jumlah_tanggungan_err = $keterangan_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_nama_muzakki = trim($_POST["nama_muzakki"]);
    if(empty($input_nama_muzakki)){
        $nama_muzakki_err = "Masukkan Nama";
    } elseif(!filter_var($input_nama_muzakki, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $nama_muzakki_err = "Masukkan Nama Yang Benar";
    } else{
        $nama_muzakki = $input_nama_muzakki;
    }

    // Validate address
    $input_jumlah_tanggungan = trim($_POST["jumlah_tanggungan"]);
    if(empty($input_jumlah_tanggungan)){
        $jumlah_tanggungan_err = "Masukkan Jumlah Tanggungan";
    } else{
        $jumlah_tanggungan = $input_jumlah_tanggungan;
    }
    
    // Validate address
    $input_keterangan = trim($_POST["keterangan"]);
    if(empty($input_keterangan)){
        $keterangan_err = "Masukkan keterangan";
    } else{
        $keterangan = $input_keterangan;
    }

    // Check input errors before inserting in database
    if(empty($nama_muzakki_err) && empty($jumlah_tanggungan_err) && empty($keterangan_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO muzakki(nama_muzakki, jumlah_tanggungan, keterangan) VALUES (?, ?, ?)";

        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_nama_muzakki, $param_jumlah_tanggungan, $param_keterangan);
            // Set parameters
            $param_nama_muzakki = $nama_muzakki;
            $param_jumlah_tanggungan = $jumlah_tanggungan;
            $param_keterangan = $keterangan;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: muzakki_index.php");
                exit();
            } else{
                echo "Terjadi Kesalahan. Coba Lagi Nanti";
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
                        <h2>Pembayaran Zakat</h2>
                    </div>
                    <p>Silahkan isi form di bawah ini kemudian submit untuk menambahkan data muzakki ke dalam database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nama_muzakki_err)) ? 'has-error' : ''; ?>">
                            <label>Nama Muzakki</label>
                            <input type="text" name="nama_muzakki" class="form-control" value="<?php echo $nama_muzakki; ?>">
                            <span class="help-block"><?php echo $nama_muzakki_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($jumlah_tanggungan_err)) ? 'has-error' : ''; ?>">
                            <label>Jumlah Tanggungan</label>
                            <input name="jumlah_tanggungan" class="form-control"><?php echo $jumlah_tanggungan; ?></input>
                            <span class="help-block"><?php echo $jumlah_tanggungan_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($keterangan_err)) ? 'has-error' : ''; ?>">
                            <label>Keterangan</label>
                            <input name="keterangan" class="form-control"><?php echo $keterangan; ?></input>
                            <span class="help-block"><?php echo $keterangan_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="muzakki_index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>