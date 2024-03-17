<!DOCTYPE HTML>  
<html>
<head>
    <title>PHP Form Validation Example</title>
    <style>
        .error {color: #FF0000;}
    </style>
    <!-- Tambahkan link CSS Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>  

<?php
session_start();

// Cek apakah pengguna sudah login atau belum
if(!isset($_SESSION['user'])) {
    header("Location: index.php"); // Redirect ke halaman login jika belum login
    exit();
}

// define variables and set to empty values
$nameErr = $emailErr = $websiteErr = $genderErr = "";
$name = $email = $website = $gender = $comment = "";
$gaji_pokok = $golongan = $tunjangan = $gaji_bersih = $pajak = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Proses validasi form

    // Validasi Nama
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
            $nameErr = "Only letters and white space allowed";
        }
    }
    
    // Validasi Email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    
    // Validasi Website
    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
            $websiteErr = "Invalid URL";
        }
    }

    // Validasi Gender
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }

    // Proses pengisian form gaji
    if(isset($_POST['submit'])) {
        $gaji_pokok = floatval($_POST['gaji_pokok']);
        $golongan = intval($_POST['golongan']);
        $tunjangan = floatval($_POST['tunjangan']);
        $gaji_bersih = floatval($_POST['gaji_bersih']);

        // Menghitung tunjangan berdasarkan golongan
        if ($golongan == 1) {
            $tunjangan = 0.35 * $gaji_pokok;
        } elseif ($golongan == 2) {
            $tunjangan = 0.25 * $gaji_pokok;
        } else {
            $tunjangan = 0.15 * $gaji_pokok;
        }

        // Menghitung pajak
        if ($gaji_pokok >= 1000000) {
            $pajak = 0.05 * $gaji_pokok;
        } else {
            $pajak = 50000.0;
        }

        // Menghitung gaji bersih
        $gaji_bersih = $gaji_pokok + $tunjangan - $pajak;
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<div class="container">
    <h2 class="mt-3">PHP Form Validation Example-APP Penghitung Penghasilan Bersih</h2>
    <p><span class="error">* required field</span></p>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name;?>">
            <span class="error">* <?php echo $nameErr;?></span>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" class="form-control" id="email" name="email" value="<?php echo $email;?>">
            <span class="error">* <?php echo $emailErr;?></span>
        </div>
        <div class="form-group">
            <label for="website">Website:</label>
            <input type="text" class="form-control" id="website" name="website" value="<?php echo $website;?>">
            <span class="error"><?php echo $websiteErr;?></span>
        </div>
        <div class="form-group">
            <label for="gaji_pokok">Besar gaji pokok:</label>
            <input type="text" class="form-control" id="gaji_pokok" name="gaji_pokok" value="<?php echo $gaji_pokok;?>" required>
        </div>
        <div class="form-group">
            <label for="golongan">Golongan:</label>
            <input type="text" class="form-control" id="golongan" name="golongan" value="<?php echo $golongan;?>" required>
        </div>
        <div class="form-group">
            <label for="tunjangan">Besar Tunjangan:</label>
            <input type="text" class="form-control" id="tunjangan" name="tunjangan" value="<?php echo $tunjangan;?>" required>
        </div>
        <div class="form-group">
            <label for="gaji_bersih">Gaji Bersih:</label>
            <input type="text" class="form-control" id="gaji_bersih" name="gaji_bersih" value="<?php echo $gaji_bersih;?>" required>
        </div>
        <div class="form-group">
            <label for="comment">Comment:</label>
            <textarea class="form-control" id="comment" name="comment" rows="5"><?php echo $comment;?></textarea>
        </div>
        <div class="form-group">
            <label>Gender:</label><br>
            <div class="form-check-inline">
                <input type="radio" class="form-check-input" name="gender" <?php if

 (isset($gender) && $gender=="female") echo "checked";?> value="female">
                <label class="form-check-label">Female</label>
            </div>
            <div class="form-check-inline">
                <input type="radio" class="form-check-input" name="gender" <?php if (isset($gender) && $gender=="male") echo "checked";?> value="male">
                <label class="form-check-label">Male</label>
            </div>
            <span class="error">* <?php echo $genderErr;?></span>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Submit</button>  
    </form>

    <?php
    echo "<h2>Your Input:</h2>";
    echo "<p>Nama: $name</p>";
    echo "<p>Email: $email</p>";
    echo '<p>Website: <a href="'.$website.'">'.$website.'</a></p>';
    echo "<p>Komentar: $comment</p>";
    echo "<p>Besar gaji pokok: $gaji_pokok</p>";
    echo "<p>Golongan: $golongan</p>";
    echo "<p>Besar Tunjangan: $tunjangan</p>";
    echo "<p>Pajak: $pajak</p>";
    echo "<p>Gaji Bersih: $gaji_bersih</p>";
    echo "<p>Jenis Kelamin: $gender</p>";
    ?>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<!-- Tambahkan script JS Bootstrap -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
```
