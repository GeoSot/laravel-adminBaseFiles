<?php

return [
    'general' => [
        'menuTitle'   => 'Queues',
        'singular'    => 'Queue',
        'plural'      => 'Queues',
        'failed_jobs' => 'Failed jobs',
        'jobs'        => 'Jobs',
    ],
    'fields' => [
        'id'           => 'ID',
        'queue'        => 'Queue',
        'name'         => 'Name',
        'attempts'     => 'Attempts',
        'reserved_at'  => 'Reserved at',
        'available_at' => 'Available at',
        'created_at'   => 'Created at',
        'actions'      => 'Actions',
        'connection'   => 'Connection',
        'payload'      => 'Payload',
        'exception'    => 'Exception',
        'failed_at'    => 'Failed at',
    ],
    'buttons' => [
        'retryAll' => 'Retry Failed Jobs',
        'flushAll' => 'Flush Failed Jobs',
        'retry'    => 'Retry Job',
        'flush'    => 'Flush Job',
        'details'  => 'Details',
    ],
];
