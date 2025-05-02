<?php

return [
    'required' => ':attribute harus diisi.',
    'email' => 'Format :attribute tidak valid.',
    'alpha_num' => 'Format :attribute tidak valid, gunakan hanya huruf dan angka.',
    'regex' => 'Format :attribute tidak valid.',
    'unique' => ':attribute sudah digunakan.',
    'numeric' => ':attribute sudah digunakan.',
    'max' => [
        'string' => ':attribute terlalu panjang, maksimal :max karakter.',
    ],
    'min' => [
        'string' => ':attribute terlalu pendek, minimal :min karakter.',
    ],
    'gt' => [
        'numeric' => ':attribute harus lebih dari :value'
    ],

    // 'custom' => [
    //     'email' => [
    //         'required' => 'Alamat email harus diisi.',
    //     ],
    // ],
    'attributes' => [
        'username' => 'ID Pengguna',
        'name' => 'Nama',
        'email' => 'Email',
        'phone' => 'No Telepon',
        'role' => 'Role',
        'address' => 'Alamat',
        'date' => 'Tanggal',
        'description' => 'Deskripsi',
        'category_id' => 'Kategori',
        'notes' => 'Catatan',
        'amount' => 'Jumlah',
        'customer_name' => 'Nama Pelanggan',
        'customer_phone' => 'No Telepon',
        'customer_address' => 'Alamat',
        'company_code' => 'Kode Perusahaan',
        'company_name' => 'Nama Perusahaan',
        'company_phone' => 'No Telepon',
        'company_address' => 'Alamat',
        'password' => 'Kata Sandi',
        'device' => 'Perangkat',
        'equipments' => 'Kelengkapan',
    ],
];
