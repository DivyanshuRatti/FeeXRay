<?php

require __DIR__ . '/../src/auth.php';
requireLogin();

// ---- PLACEHOLDER DATA ----
// Fake numbers for designing the page.
// Later these come from the database and the fee calculator.
$summary = [
    'total_invested' => 25000,
    'yearly_fees'    => 480,
    'average_mer'    => 0.0192,
];

$portfolios = [
    ['id' => 1, 'name' => 'My TFSA',   'account_type' => 'TFSA', 'value' => 15000, 'yearly_fee' => 300],
    ['id' => 2, 'name' => 'Work RRSP', 'account_type' => 'RRSP', 'value' => 10000, 'yearly_fee' => 180],
];
// ---- END PLACEHOLDER ----

require __DIR__ . '/../views/dashboard.php';

