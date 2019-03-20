<?php
return [
    'general' => [
        'menuTitle' => 'Tickets',
        'singular' => 'Ticket',
        'plural' => 'Tickets',
        'respond' => 'respond to Ticket'
    ],
    'fields' => [
        'id' => 'ID',
        'title' => 'Title',
        'lastReply' => 'Last reply',
        'last_response_mail' => 'Last response email address',
        'created_at' => 'Created at',
        'ticket_status' => 'Status',
        'openedBy' => 'Opened by',
        'supporters' => 'Assigned to',
        'code' => 'Code',
    ],


    'buttons' => [
        'new' => 'Open new Ticket',
        'back' => 'Back to Tickets',
    ],
    'filters' => [
        'filterByStatus' => 'Filter By Status',
        'search' => 'Search',
        'searchBtn' => 'Go',
    ],
    'errorMessages' => [
        'titleIsMandatory' => 'The title is mandatory',
        'ticketIsCompletedTitle' => 'Ticket is Completed',
        'ticketIsCompletedMsg' => 'You Cannot add new messages on a completed Ticket<br/>Please try to Open an new Ticket',
    ],
    'notifications' => [
        'sender' => 'Sender',
        'date' => 'Date',
        'attachments' => 'Attachments',
    ],


];
