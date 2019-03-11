<?php
$config['apps'] = 'public';
 $errno = isset($_SESSION['error_page']) ? $_SESSION['error_page'] : "404";
 switch($errno){
  case "403":
   $message = "Halaman Tidak Tersedia Untuk Pengguna Saat Ini";
  break;
  
  default:
   $message = "Halaman Tidak Ditemukan";
  break;
 }
 if(isset($_SESSION['error_page'])) unset($_SESSION['error_page']);
$config['page_title'] = $message;

$template->start_block('content');
?>
     <h1 class="text-danger"><?= $errno ?></h1>
     <h3 class="text-uppercase"><?= $message ?></h3>
     <p class="text-muted">Jika Anda merasa ini adalah kesalahan, mohon hubungi Administrator Sistem.</p>
     <a href="<?= module_url('default') ?>" class="btn btn-danger">Kembali ke Halaman Utama</a> </div>
   <footer class="footer text-center">2017 © <?= $config['website_name'] ?></footer>
<?php
$template->end_block('content');