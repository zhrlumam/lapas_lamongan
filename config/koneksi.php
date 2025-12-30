<?php
$conn = mysqli_connect("localhost", "root", "", "lapas_lamongan");

if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}
