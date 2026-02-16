<?php
$hash = '$2y$10$Xf37OIEvxKiL0yIxDrrQfukvBv.RlfTR34EuHuz8hAeJD/HT3tyEO';
$password = '123456';

if (password_verify($password, $hash)) {
    echo "Password '123456' matches the hash.\n";
} else {
    echo "Password '123456' DOES NOT match the hash.\n";
}
