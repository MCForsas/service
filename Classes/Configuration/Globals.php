<?php
  /*
    Here we are defining global variables like domains, database info and global configs.
  */
  define('DOMAIN','localhost/e');
  $allowedPostDomains =
  array(
    'localhost',
    'wantit.com'
  );
  define('ALLOWED_POST_DOMAINS',$allowedPostDomains);
  //Database info
  define('DATABASE_HOST','localhost');
  define('DATABASE_NAME','wantit');
  define('DATABASE_USER','root');
  define('DATABASE_PASSWORD','');
?>
