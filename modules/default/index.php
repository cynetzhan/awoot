<?php 
// If you want to access a page with a template, the $config['apps'] must be defined with template available inside the 'template' folder. Otherwise, it will be just presented as it is.
$config['apps'] = "public";
$template->start_block('content'); ?>
 <h3>Selamat Datang!</h3>
<?php $template->end_block('content'); ?>