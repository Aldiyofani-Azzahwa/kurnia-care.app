<?php

return [
    'bank_name' => env('PAYMENT_BANK_NAME', 'Bank BCA'),
    'account_number' => env('PAYMENT_ACCOUNT_NUMBER', '1234567890'),
    'account_holder' => env('PAYMENT_ACCOUNT_HOLDER', 'Klinik Kurnia Care'),

    'dp_amount' => (int) env('PAYMENT_DP_AMOUNT', 100000),

    'payment_note' => env(
        'PAYMENT_NOTE',
        'Pembayaran awal berupa DP Rp100.000. Pelunasan dilakukan setelah tindakan khitan selesai.'
    ),
];