<?php

return [
    'apps' => [
        [
            'id' => env('PUSHER_APP_ID'),
            'name' => env('APP_NAME'),
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            // 'enable_client_messages' => true,
            'enable_client_messages' => true,
            'enable_statistics' => true,
        ],
    ],
    'app_provider' => BeyondCode\LaravelWebSockets\Apps\ConfigAppProvider::class,

    'allowed_origins' => [
        //
    ],
    'max_request_size_in_kb' => 250,
    'path' => 'laravel-websockets',
    'statistics' => [
        /*
         * This model will be used to store the statistics of the WebSocketsServer.
         * The only requirement is that the model should extend
         * `WebSocketsStatisticsEntry` provided by this package.
         */
        'model' => \BeyondCode\LaravelWebSockets\Statistics\Models\WebSocketsStatisticsEntry::class,

        /*
         * Here you can specify the interval in seconds at which statistics should be logged.
         */
        'interval_in_seconds' => 60,

        /*
         * When the clean-command is executed, all recorded statistics older than
         * the number of days specified here will be deleted.
         */
        'delete_statistics_older_than_days' => 60,
    ],


    'ssl' => [
        // 'local_cert' => null,
        'local_cert' => env('LOCAL_CERT', null),
        // 'local_pk' => null,
        'local_pk' => env('LOCAL_KEY', null),
        'passphrase' => null
        // 'verify_peer' => false
        // 'verify_peer_name' => false,
    ],
];
