<?php

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function normalizeCardType(string $cardType): string
{
    $typeMap = [
        'retail' => 'Retail',
        'dining' => 'Dining',
        'travel' => 'Travel',
        'entertainment' => 'Entertainment',
        'Retail' => 'Retail',
        'Dining' => 'Dining',
        'Travel' => 'Travel',
        'Entertainment' => 'Entertainment',
    ];

    $normalized = strtolower(trim($cardType));

    return $typeMap[$normalized] ?? ucfirst(strtolower(trim($cardType)));
}

function validateCardId($cardId): ?int
{
    if ($cardId === null || $cardId === '') {
        return null;
    }

    if (!is_numeric($cardId)) {
        return null;
    }

    $value = (int) $cardId;
    if ($value <= 0) {
        return null;
    }

    return $value;
}

function validateCardName(string $cardName): string
{
    $trimmed = trim($cardName);

    if ($trimmed === '') {
        return 'Card Name is required.';
    }

    if (mb_strlen($trimmed) > 100) {
        return 'Card Name must be 100 characters or fewer.';
    }

    return '';
}

function validateCardType($cardType): string
{
    $allowed = ['retail', 'dining', 'travel', 'entertainment'];
    $normalized = strtolower(trim((string) $cardType));

    if (!in_array($normalized, $allowed, true)) {
        return 'Select a valid Card Type.';
    }

    return '';
}

function validateCardValue($cardValue): string
{
    if (!is_numeric($cardValue)) {
        return 'Card Value must be a positive number.';
    }

    $value = (float) $cardValue;
    if ($value <= 0) {
        return 'Card Value must be a positive number.';
    }

    return '';
}

function validatePointsRequired($pointsRequired): string
{
    if (!is_numeric($pointsRequired)) {
        return 'Points Required must be a positive whole number.';
    }

    $value = (float) $pointsRequired;
    if ($value <= 0 || floor($value) != $value) {
        return 'Points Required must be a positive whole number.';
    }

    return '';
}

function validateGiftCardData(array $data): array
{
    $cardName = trim((string) ($data['card_name'] ?? $data['card-name'] ?? ''));
    $cardType = trim((string) ($data['card_type'] ?? $data['card-type'] ?? ''));
    $cardValue = $data['card_value'] ?? $data['card-value'] ?? '';
    $pointsRequired = $data['points_required'] ?? $data['points-required'] ?? '';

    $errors = [];
    $nameError = validateCardName($cardName);
    if ($nameError !== '') {
        $errors['card_name'] = $nameError;
    }

    $typeError = validateCardType($cardType);
    if ($typeError !== '') {
        $errors['card_type'] = $typeError;
    }

    $valueError = validateCardValue($cardValue);
    if ($valueError !== '') {
        $errors['card_value'] = $valueError;
    }

    $pointsError = validatePointsRequired($pointsRequired);
    if ($pointsError !== '') {
        $errors['points_required'] = $pointsError;
    }

    return [
        'errors' => $errors,
        'data' => [
            'card_name' => $cardName,
            'card_type' => normalizeCardType($cardType),
            'card_value' => (float) $cardValue,
            'points_required' => (int) $pointsRequired,
        ],
    ];
}
