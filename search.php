<?php

// Program to display URL of current page.
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
	$link = "https";
else
	$link = "http";
	
// Here append the common URL characters.
$link .= "://";
	
// Append the host(domain name, ip) to the URL.
$link .= $_SERVER['HTTP_HOST'];
	
// Append the requested resource location to the URL
$link .= $_SERVER['REQUEST_URI'];
	
// Print the link
echo $link;

?>

<?php

// Initialize URL to the variable
$url = 'https://www.geeksforgeeks.org/register?name=Amit&email=amit1998@gmail.com';
    
// Use parse_url() function to parse the URL 
// and return an associative array which
// contains its various components
$url_components = parse_url($url);

// Use parse_str() function to parse the
// string passed via URL
echo "<pre>";
print_r($url_components);
parse_str($url_components['query'], $params);
    
// Display result
echo ' Hi '.$params['name'];

?>
