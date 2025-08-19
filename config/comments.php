<?php

return [
'moderation' => env('COMMENT_MODERATION', true),
'rate_limit' => env('COMMENT_RATE_LIMIT', 10),
'max_length' => env('COMMENT_MAX_LENGTH', 2000),
'reply_max_length' => env('COMMENT_REPLY_MAX_LENGTH', 1000),
'auto_approve_threshold' => env('COMMENT_AUTO_APPROVE_THRESHOLD', 5),
'profanity_filter' => true,
'mentions_enabled' => true,
'email_notifications' => true,
];

