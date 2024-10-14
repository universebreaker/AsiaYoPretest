<?php

dataset('order_tests', [
    // success with usd
    [
        'payload' => [
            "id" => "A0000002",
            "name" => "Melody Holiday Inn",
            "address" => [
                "city" => "taipei-city",
                "district" => "da-an-district",
                "street" => "fuxing-south-road"
            ],
            "price" => "42",
            "currency" => "USD"
        ],
        'expected_status' => 200,
        'expected_response' => [
            "id" => "A0000002",
            "name" => "Melody Holiday Inn",
            "address" => [
                "city" => "taipei-city",
                "district" => "da-an-district",
                "street" => "fuxing-south-road"
            ],
            "price" => 1302,
            "currency" => "TWD"
        ],
        'expected_errors' => ''
    ],
    // success with twd
    [
        'payload' => [
            "id" => "A0000001",
            "name" => "Melody Holiday Inn",
            "address" => [
                "city" => "taipei-city",
                "district" => "da-an-district",
                "street" => "fuxing-south-road"
            ],
            "price" => "2000",
            "currency" => "TWD"
        ],
        'expected_status' => 200,
        'expected_response' => [
            "id" => "A0000001",
            "name" => "Melody Holiday Inn",
            "address" => [
                "city" => "taipei-city",
                "district" => "da-an-district",
                "street" => "fuxing-south-road"
            ],
            "price" => 2000,
            "currency" => "TWD"
        ],
        'expected_errors' => ''
    ],
    // fail with non-english character
    [
        'payload' => [
            "id" => "A0000003",
            "name" => "Melody Holiday Inn 宜蘭",
            "address" => [
                "city" => "yilan-city",
                "district" => "suao-township",
                "street" => "xinyi-road"
            ],
            "price" => "2000",
            "currency" => "TWD"
        ],
        'expected_status' => 400,
        'expected_response' => '',
        'expected_errors' => [
            "Name contains non-English characters"
        ]
    ],
    // fail with non-capitalized name
    [
        'payload' => [
            "id" => "A0000004",
            "name" => "melody holiday Inn",
            "address" => [
                "city" => "taipei-city",
                "district" => "da-an-district",
                "street" => "fuxing-south-road"
            ],
            "price" => "2000",
            "currency" => "TWD"
        ],
        'expected_status' => 400,
        'expected_response' => '',
        'expected_errors' => [
            "Name is not capitalized"
        ]
    ],
    // fail with twd price over 2000
    [
        'payload' => [
            "id" => "A0000005",
            "name" => "Melody Holiday Inn",
            "address" => [
                "city" => "taipei-city",
                "district" => "da-an-district",
                "street" => "fuxing-south-road"
            ],
            "price" => "500",
            "currency" => "USD"
        ],
        'expected_status' => 400,
        'expected_response' => '',
        'expected_errors' => [
            "Price is over 2000"
        ]
    ],
    // fail with price over 2000
    [
        'payload' => [
            "id" => "A0000005",
            "name" => "Melody Holiday Inn",
            "address" => [
                "city" => "taipei-city",
                "district" => "da-an-district",
                "street" => "fuxing-south-road"
            ],
            "price" => "2001",
            "currency" => "TWD"
        ],
        'expected_status' => 400,
        'expected_response' => '',
        'expected_errors' => [
            "Price is over 2000"
        ]
    ],
    // fail with incorrect currency
    [
        'payload' => [
            "id" => "A0000006",
            "name" => "Melody Holiday Inn",
            "address" => [
                "city" => "taipei-city",
                "district" => "da-an-district",
                "street" => "fuxing-south-road"
            ],
            "price" => "2000",
            "currency" => "JPY"
        ],
        'expected_status' => 400,
        'expected_response' => '',
        'expected_errors' => [
            "Currency format is wrong"
        ]
    ],
]);

it('Test', function ($payload, $expected_status, $expected_response, $expected_errors) {
    $response = $this->postJson('/api/orders', $payload);
    $response->assertStatus($expected_status);
    if ($expected_status === 200) {
        $response->assertJson($expected_response);
    } elseif ($expected_status) {
        $response->assertJson([
            "errors" => $expected_errors
        ]);
    }
})->with('order_tests');
