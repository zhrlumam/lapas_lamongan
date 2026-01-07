<?php
session_start();
include "../config/koneksi.php";

// Keamanan: Cek Login
if (!isset($_SESSION['admin_id'])) { 
    header("Location: index.php"); 
    exit; 
}

include "layout/role_check.php";
check_role(['Registrasi']);

// FORMAT FILENAME
$filename = "Data_Kunjungan_" . date('Y-m-d_H-i') . ".xls";

// HEADER untuk file Excel (XML Spreadsheet 2003 Format)
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// --- LOGIKA FILTER ---
$tgl_mulai = $_GET['tgl_mulai'] ?? '';
$tgl_selesai = $_GET['tgl_selesai'] ?? '';
$search = $_GET['search'] ?? '';

$query_str = "SELECT * FROM kunjungan WHERE 1=1";
$params = [];

if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
    $query_str .= " AND tanggal_kunjungan BETWEEN ? AND ?";
    $params[] = $tgl_mulai;
    $params[] = $tgl_selesai;
}

if (!empty($search)) {
    $query_str .= " AND (nama_wbp LIKE ? OR no_antrean LIKE ? OR id IN (SELECT kunjungan_id FROM kunjungan_pengunjung WHERE nik_pengunjung LIKE ? OR nama_pengunjung LIKE ?))";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
}

$query_str .= " ORDER BY tanggal_kunjungan DESC, sesi ASC, no_antrean ASC";

$stmt = $pdo->prepare($query_str);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

function tgl_indo($tanggal) {
    $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $ts = strtotime($tanggal);
    return date('d', $ts) . " " . $bulan[(int)date('m', $ts)] . " " . date('Y', $ts);
}

function xml_ent($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// OUTPUT XML CONTENT
echo '<?xml version="1.0"?>';
?>
<?php echo '<?mso-application progid="Excel.Sheet"?>'; ?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>Sistem Lapas Lamongan</Author>
  <Created><?= date('Y-m-d\TH:i:s\Z') ?></Created>
 </DocumentProperties>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="sHeader">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#FFFFFF" ss:Bold="1"/>
   <Interior ss:Color="#07213D" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="sData">
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Alignment ss:Vertical="Top"/>
  </Style>
  <Style ss:ID="sDataCenter">
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Alignment ss:Horizontal="Center" ss:Vertical="Top"/>
  </Style>
  <Style ss:ID="sText">
   <NumberFormat ss:Format="@"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Alignment ss:Vertical="Top"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Data Kunjungan">
  <Table ss:ExpandedColumnCount="16" x:FullColumns="1" x:FullRows="1" ss:DefaultRowHeight="15">
   <Column ss:AutoFitWidth="0" ss:Width="30"/>
   <Column ss:AutoFitWidth="0" ss:Width="100"/>
   <Column ss:AutoFitWidth="0" ss:Width="60"/>
   <Column ss:AutoFitWidth="0" ss:Width="50"/>
   <Column ss:AutoFitWidth="0" ss:Width="150"/>
   <Column ss:AutoFitWidth="0" ss:Width="80"/>
   <Column ss:AutoFitWidth="0" ss:Width="150"/>
   <Column ss:AutoFitWidth="0" ss:Width="120"/>
   <Column ss:AutoFitWidth="0" ss:Width="120"/>
   <Column ss:AutoFitWidth="0" ss:Width="120"/>
   <Column ss:AutoFitWidth="0" ss:Width="120"/>
   <Column ss:AutoFitWidth="0" ss:Width="120"/>
   <Column ss:AutoFitWidth="0" ss:Width="120"/>
   <Column ss:AutoFitWidth="0" ss:Width="120"/>
   <Column ss:AutoFitWidth="0" ss:Width="120"/>
   <Column ss:AutoFitWidth="0" ss:Width="120"/>
   <Row ss:Height="20">
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">No</Data></Cell>
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">Tanggal Kunjungan</Data></Cell>
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">No Antrean</Data></Cell>
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">Sesi</Data></Cell>
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">Nama WBP</Data></Cell>
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">Status WBP</Data></Cell>
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">Pengunjung Utama</Data></Cell>
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">NIK Utama</Data></Cell>
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">Pengikut 1</Data></Cell>
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">NIK 1</Data></Cell>
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">Pengikut 2</Data></Cell>
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">NIK 2</Data></Cell>
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">Pengikut 3</Data></Cell>
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">NIK 3</Data></Cell>
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">Pengikut 4</Data></Cell>
    <Cell ss:StyleID="sHeader"><Data ss:Type="String">NIK 4</Data></Cell>
   </Row>
   <?php 
   $no = 1;
   foreach($result as $k): 
       $stmt_p = $pdo->prepare("SELECT * FROM kunjungan_pengunjung WHERE kunjungan_id = ? ORDER BY id ASC");
       $stmt_p->execute([$k['id']]);
       $p_list = $stmt_p->fetchAll(PDO::FETCH_ASSOC);
       $utama = $p_list[0] ?? ['nama_pengunjung'=>'-','nik_pengunjung'=>'-'];
   ?>
   <Row>
    <Cell ss:StyleID="sDataCenter"><Data ss:Type="Number"><?= $no++ ?></Data></Cell>
    <Cell ss:StyleID="sData"><Data ss:Type="String"><?= tgl_indo($k['tanggal_kunjungan']) ?></Data></Cell>
    <Cell ss:StyleID="sDataCenter"><Data ss:Type="String"><?= xml_ent($k['no_antrean']) ?></Data></Cell>
    <Cell ss:StyleID="sDataCenter"><Data ss:Type="String"><?= xml_ent($k['sesi']) ?></Data></Cell>
    <Cell ss:StyleID="sData"><Data ss:Type="String"><?= xml_ent($k['nama_wbp']) ?></Data></Cell>
    <Cell ss:StyleID="sData"><Data ss:Type="String"><?= xml_ent($k['status_wbp']) ?></Data></Cell>
    <Cell ss:StyleID="sData"><Data ss:Type="String"><?= xml_ent($utama['nama_pengunjung']) ?></Data></Cell>
    <Cell ss:StyleID="sText"><Data ss:Type="String"><?= xml_ent($utama['nik_pengunjung']) ?></Data></Cell>
    
    <?php for($i=1; $i<=4; $i++): 
        $p = $p_list[$i] ?? null;
    ?>
    <Cell ss:StyleID="sData"><Data ss:Type="String"><?= $p ? xml_ent($p['nama_pengunjung']) : '-' ?></Data></Cell>
    <Cell ss:StyleID="sText"><Data ss:Type="String"><?= $p ? xml_ent($p['nik_pengunjung']) : '-' ?></Data></Cell>
    <?php endfor; ?>
   </Row>
   <?php endforeach; ?>
  </Table>
 </Worksheet>
</Workbook>
