<?php

/**
 * Converts bytes into human readable file size.
 *
 * @param string $bytes
 * @return string human readable file size (2,87 МB)
 * @author Mogilev Arseny
 */

// Define a function to convert bytes into a human readable file size
function FileSizeConvert($bytes)
{
    $result = NULL;   // Initialize the result variable as NULL

    // Convert the input bytes into a float value
    $bytes = floatval($bytes);

    // Define an array to store the different units of bytes and their corresponding values in bytes
    $arBytes = [
        0 => [
            "UNIT"  =>  "TB",                     // Terabytes
            "VALUE" =>  pow(1024, 4)              // Value of a Terabyte in bytes
        ],
        1 => [
            "UNIT"  =>  "GB",                     // Gigabytes
            "VALUE" =>  pow(1024, 3)              // Value of a Gigabyte in bytes
        ],
        2 => [
            "UNIT"  =>  "MB",                     // Megabytes
            "VALUE" =>  pow(1024, 2)              // Value of a Megabyte in bytes
        ],
        3 => [
            "UNIT"  =>  "KB",                     // Kilobytes
            "VALUE" =>  1024                      // Value of a Kilobyte in bytes
        ],
        4 => [
            "UNIT"  =>  "B",                      // Bytes
            "VALUE" =>  1                         // Value of a Byte in bytes
        ],
    ];

    // Iterate over the array of units and their values
    foreach ($arBytes as $arItem) {
        // If the input bytes is greater than or equal to the current unit value
        if ($bytes >= $arItem["VALUE"]) {
            // Divide the bytes by the unit value to get the result in the current unit
            $result = $bytes / $arItem["VALUE"];

            // If the current unit is MB, round the result to 2 decimal places
            // Otherwise, round the result to no decimal places
            // Replace the decimal point with a comma for the result
            // Append the unit to the result
            if ($arItem["UNIT"] == "MB") {
                $result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
            } else {
                $result = str_replace(".", ",", strval(round($result, 0))) . " " . $arItem["UNIT"];
            }

            // Stop the loop as the result is found
            break;
        }
    }

    // Return the result
    return $result;
}
