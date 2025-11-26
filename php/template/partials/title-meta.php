<?php
$filename = basename($_SERVER['PHP_SELF'], '.php');

$acronyms = ['ui', 'ai', 'js', 'api', 'css', 'html', 'php', 'seo'];

if ($filename === 'index') {
    $title = 'Deals Dashboard';
} else {
    $parts = explode('-', str_replace('ui-', '', strtolower($filename)));

    $hasIcon = false;
    $hasChart = false;
    $cleaned_parts = [];

    foreach ($parts as $part) {
        if ($part === 'icon') {
            $hasIcon = true;
            continue;
        }
        if ($part === 'chart') {
            $hasChart = true;
            continue;
        }
        if ($part === 'all') {
            continue; 
        }
        $cleaned_parts[] = $part;
    }

    $formatted_parts = array_map(function ($word) use ($acronyms) {
        return in_array($word, $acronyms) ? strtoupper($word) : ucfirst($word);
    }, $cleaned_parts);

    if ($hasIcon) {
        $formatted_parts[] = 'Icons';
    }

    if ($hasChart) {
        $formatted_parts[] = 'Charts';
    }

    $title = implode(' ', $formatted_parts);
}
?>
    
	<!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> <?= $title ?> | CRMS - Advanced Bootstrap 5 Admin Template for Customer Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Streamline your business with our advanced CRM template. Easily integrate and customize to manage sales, support, and customer interactions efficiently. Perfect for any business size">
	<meta name="keywords" content="Advanced CRM template, customer relationship management, business CRM, sales optimization, customer support software, CRM integration, customizable CRM, business tools, enterprise CRM solutions">
	<meta name="author" content="Dreams Technologies">
	<meta name="robots" content="index, follow">
    