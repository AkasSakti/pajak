<?php
session_start();

// Cek apakah pengguna sudah login atau belum
if(!isset($_SESSION['user'])) {
    header("Location: index.php"); // Redirect ke halaman login jika belum login
    exit();
}
?>
<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  
<h2>Welcome, <?php echo $_SESSION['user']; ?></h2>
<?php
// define variables and set to empty values
$nameErr = $emailErr = $genderErr = $websiteErr = "";
$name = $email = $gender = $comment = $website = "";
$gaji_pokok = $golongan = $tunjangan = $gaji_bersih = $pajak = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed";
    }
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
  }
    
  if (empty($_POST["website"])) {
    $website = "";
  } else {
    $website = test_input($_POST["website"]);
    // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
      $websiteErr = "Invalid URL";
    }
  }

  if (empty($_POST["comment"])) {
    $comment = "";
  } else {
    $comment = test_input($_POST["comment"]);
  }

  if (empty($_POST["gender"])) {
    $genderErr = "Gender is required";
  } else {
    $gender = test_input($_POST["gender"]);
  }

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

<h2>PHP Form Validation Example</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Name: <input type="text" name="name" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  E-mail: <input type="text" name="email" value="<?php echo $email;?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  Website: <input type="text" name="website" value="<?php echo $website;?>">
  <span class="error"><?php echo $websiteErr;?></span>
  <br><br>
  <label for="gaji_pokok">Besar gaji pokok:</label>
  <input type="text" id="gaji_pokok" name="gaji_pokok" value="<?php echo $gaji_pokok;?>" required><br><br>
        
  <label for="golongan">Golongan:</label>
  <input type="text" id="golongan" name="golongan" value="<?php echo $golongan;?>" required><br><br>
        
  <label for="tunjangan">Besar Tunjangan:</label>
  <input type="text" id="tunjangan" name="tunjangan" value="<?php echo $tunjangan;?>" required><br><br>
        
  <label for="gaji_bersih">Gaji Bersih:</label>
  <input type="text" id="gaji_bersih" name="gaji_bersih" value="<?php echo $gaji_bersih;?>" required><br><br>
        
  Comment: <textarea name="comment" rows="5" cols="40"><?php echo $comment;?></textarea>
  <br><br>
  Gender:
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="female") echo "checked";?> value="female">Female
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="male") echo "checked";?> value="male">Male
  <span class="error">* <?php echo $genderErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>
<?php
echo "<h2>Your Input:</h2>";
echo "Nama: $name";
echo "<br>";
echo "Email: $email";
echo "<br>";
echo 'Link: <a href="'.$website.'">Link</a>';
echo "<br>";
echo "Komentar: $comment";
echo "<br>";
echo "<p>Besar gaji pokok: $gaji_pokok</p>";
echo "<p>Golongan: $golongan</p>";
echo "<p>Besar Tunjangan: $tunjangan</p>";
echo "<p>Pajak: $pajak</p>";
echo "<p>Gaji Bersih: $gaji_bersih</p>";
echo "<br>";
echo "Jenis Kelamin: $gender";
?>
<a href="logout.php">Logout</a>
</body>
</html>