<?php
$link = $_SERVER[ 'PHP_SELF' ];
$link_array = explode( '/', $link );
$page = end( $link_array );
?> 

<?php
if ($page === 'layout-mini.php') {
    echo '<html lang="en" data-layout="mini">';
} elseif ($page === 'layout-hoverview.php') {
    echo '<html lang="en" data-layout="hoverview">';
} elseif ($page === 'layout-hidden.php') {
    echo '<html lang="en" data-layout="hidden">'; 
} elseif ($page === 'layout-fullwidth.php') {
    echo '<html lang="en" data-layout="full-width">';   
} elseif ($page === 'layout-rtl.php') {
    echo '<html lang="en" dir="rtl">';    
} elseif ($page === 'layout-dark.php') {
    echo '<html lang="en" data-bs-theme="dark">';          
} else {
    echo '<html lang="en">';
}
?>
