<?php

/**
 * A simple function that gets the filesize 
 * from a file submitted with an URL
 */

// Define a function to get the filesize of a file at a given URL
function Get_Filesize($url)
{
    $ch = curl_init($url);   // Initialize a new cURL session with the given URL

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);   // Set an option for a cURL transfer to return the transfer as a string of the return value of curl_exec() instead of outputting it directly
    curl_setopt($ch, CURLOPT_HEADER, TRUE);           // Set an option for a cURL transfer to include the header in the output
    curl_setopt($ch, CURLOPT_NOBODY, TRUE);           // Set an option for a cURL transfer to exclude the body from the output

    $data = curl_exec($ch);                           // Execute the cURL session and store the result in the data variable
    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);   // Get the size of the file from the content-length header of the cURL session

    curl_close($ch);                                  // Close the cURL session

    return $size;                                     // Return the size of the file
}

?>
