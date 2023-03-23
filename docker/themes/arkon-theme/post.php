<?php
$SENDER_EMAIL = 'kharman123@gmail.com';
$RECIPIENT_EMAIL = 'kharman123+5@gmail.com';
$msg = '';
foreach($_POST as $name => $val)
    $msg .="<b>$name</b>: $val<br/>";

require 'vendor/autoload.php';
use \Mailjet\Resources;
$api_key = '1c48a16a2a66246cab5916c6b158ae81';
$secret_key = '2f51bc72ee88f0da76b75fb4270f15d3';
$mj = new \Mailjet\Client($api_key, $secret_key,true,['version' => 'v3.1']);
$body = [
    'Messages' => [
        [
            'From' => [
                'Email' => "$SENDER_EMAIL",
                'Name' => "Me"
            ],
            'To' => [
                [
                    'Email' => "$RECIPIENT_EMAIL",
                    'Name' => "You"
                ]
            ],
            'Subject' => "Wordpress form data Email!",
            'TextPart' => "Greetings from Mailjet!",
            'HTMLPart' => "<h3>Dear passenger data request</h3><br><hr>$msg"
        ]
    ]
];
$response = $mj->post(Resources::$Email, ['body' => $body]);
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.16.10/dist/css/uikit.min.css">
<?php
if($response->success()){?>
    <div class="uk-section"></div>
    <div class="uk-card uk-card-default uk-card-body uk-width-1-2@m uk-margin-auto">
        <div class="uk-alert-success uk-alert" uk-alert>
            <p>Data form sended! thanks</p>
            <a href="/">Return to home</a>
        </div>
    </div>
<?php
}
//$response->success();
//
//echo '<pre>' . print_r($response->getData(),true) . '</pre>';

