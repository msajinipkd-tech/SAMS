<?php

function validate($post) {
    $data = [
        'card_number' => trim($post['card_number'] ?? ''),
        'card_expiry' => trim($post['card_expiry'] ?? ''),
        'card_cvv' => trim($post['card_cvv'] ?? ''),
        'card_number_err' => '',
        'card_expiry_err' => '',
        'card_cvv_err' => ''
    ];

    // Validate Card Number
    if (empty($data['card_number'])) {
        $data['card_number_err'] = 'Please enter card number';
    } elseif (!is_numeric($data['card_number'])) {
        $data['card_number_err'] = 'Card number must be numeric';
    } elseif (strlen($data['card_number']) < 13 || strlen($data['card_number']) > 16) {
        $data['card_number_err'] = 'Card number must be between 13 and 16 digits';
    }

    // Validate Expiration Date
    if (empty($data['card_expiry'])) {
        $data['card_expiry_err'] = 'Please enter expiration date';
    } else {
        if (!preg_match('/^(0[1-9]|1[0-2])\/?([0-9]{2}|[0-9]{4})$/', $data['card_expiry'], $matches)) {
             $data['card_expiry_err'] = 'Invalid format (MM/YY or MM/YYYY)';
        } else {
            $month = $matches[1];
            $year = $matches[2];
            if (strlen($year) == 2) {
                $year = '20' . $year;
            }
            $currentYear = date('Y');
            $currentMonth = date('m');

            if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
                $data['card_expiry_err'] = 'Card has expired';
            }
        }
    }

    // Validate CVV
    if (empty($data['card_cvv'])) {
         $data['card_cvv_err'] = 'Please enter CVV';
    } elseif (!is_numeric($data['card_cvv'])) {
        $data['card_cvv_err'] = 'CVV must be numeric';
    } elseif (strlen($data['card_cvv']) < 3 || strlen($data['card_cvv']) > 4) {
        $data['card_cvv_err'] = 'CVV must be 3 or 4 digits';
    }

    return $data;
}

$tests = [
    'Valid Data' => [
        'input' => ['card_number' => '1234567890123', 'card_expiry' => '12/30', 'card_cvv' => '123'],
        'expect_errors' => false
    ],
    'Invalid Card (Non-numeric)' => [
        'input' => ['card_number' => '1234abc890123', 'card_expiry' => '12/30', 'card_cvv' => '123'],
        'expect_errors' => true
    ],
    'Invalid Card (Length)' => [
        'input' => ['card_number' => '123', 'card_expiry' => '12/30', 'card_cvv' => '123'],
        'expect_errors' => true
    ],
    'Invalid Expiry (Format)' => [
        'input' => ['card_number' => '1234567890123', 'card_expiry' => '13/30', 'card_cvv' => '123'],
        'expect_errors' => true
    ],
    'Invalid Expiry (Expired)' => [
        'input' => ['card_number' => '1234567890123', 'card_expiry' => '01/20', 'card_cvv' => '123'],
        'expect_errors' => true
    ],
    'Invalid CVV (Length)' => [
        'input' => ['card_number' => '1234567890123', 'card_expiry' => '12/30', 'card_cvv' => '12'],
        'expect_errors' => true
    ]
];

foreach ($tests as $name => $test) {
    $result = validate($test['input']);
    $hasErrors = !empty($result['card_number_err']) || !empty($result['card_expiry_err']) || !empty($result['card_cvv_err']);
    
    echo "Test: $name\n";
    if ($hasErrors === $test['expect_errors']) {
        echo "PASS\n";
    } else {
        echo "FAIL\n";
        print_r($result);
    }
    echo "----------------\n";
}
