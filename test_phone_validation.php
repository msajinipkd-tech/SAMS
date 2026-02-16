<?php

function validatePhone($phone) {
    if (empty($phone)) {
        return 'Please enter phone number';
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        return 'Invalid phone number (10 digits required)';
    } else {
        return 'VALID';
    }
}

$tests = [
    'Valid Phone' => '1234567890',
    'Short Phone' => '123456789',
    'Long Phone' => '12345678901',
    'Non-numeric Phone' => '12345abcde',
    'Empty Phone' => ''
];

foreach ($tests as $name => $input) {
    $result = validatePhone($input);
    echo "Test: $name | Input: '$input' | Result: $result\n";
}
