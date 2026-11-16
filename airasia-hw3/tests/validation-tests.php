<?php
require_once __DIR__ . '/../includes/validation.php';

$tests = [
    [
        'name' => 'Valid Gift Card data',
        'input' => [
            'card_name' => 'Amazon Gift Card',
            'card_type' => 'retail',
            'card_value' => '25.00',
            'points_required' => '10000',
        ],
        'expectedErrors' => [],
    ],
    [
        'name' => 'Blank Card Name',
        'input' => [
            'card_name' => '',
            'card_type' => 'retail',
            'card_value' => '25.00',
            'points_required' => '10000',
        ],
        'expectedErrors' => ['card_name'],
    ],
    [
        'name' => 'Altered or invalid Card Type',
        'input' => [
            'card_name' => 'Amazon Gift Card',
            'card_type' => 'wellness',
            'card_value' => '25.00',
            'points_required' => '10000',
        ],
        'expectedErrors' => ['card_type'],
    ],
    [
        'name' => 'Zero or negative Card Value',
        'input' => [
            'card_name' => 'Amazon Gift Card',
            'card_type' => 'retail',
            'card_value' => '0',
            'points_required' => '10000',
        ],
        'expectedErrors' => ['card_value'],
    ],
    [
        'name' => 'Invalid Points Required',
        'input' => [
            'card_name' => 'Amazon Gift Card',
            'card_type' => 'retail',
            'card_value' => '25.00',
            'points_required' => '0',
        ],
        'expectedErrors' => ['points_required'],
    ],
];

foreach ($tests as $test) {
    $result = validateGiftCardData($test['input']);
    $actualKeys = array_keys($result['errors']);
    $passed = $actualKeys === $test['expectedErrors'];

    echo '<p>' . e($test['name']) . ': ' . ($passed ? 'PASS' : 'FAIL') . '</p>';
}
