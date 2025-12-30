<?php
include "../config/koneksi.php";

$password = password_hash("admin123", PASSWORD_DEFAULT);

mysqli_query($conn, "
  UPDATE admin 
  SET password='$password' 
  WHERE username='admin'
");

echo "PASSWORD BERHASIL DIRESET";
