<?php
return [
    'adminEmail' => 'admin@summittosea.no',
    'authKeyLifeTime' => 24 * 30, // hours
        'authCredentials' => [ 
        'facebook' => [
                'clientId' => '837897742926810',
                'clientSecret' => '0cec9ff42991f89f93e34f82df373601',
            ]
        ],
    'visitInterval' => 0.01, //hours: if less time passed from the moment User entered Place's code last time, new Visit is not created
    'imgDir' => 'images/',
];
