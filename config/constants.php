<?php 

// глобальные константы

define('RATE_LIMIT_PER_USER_PER_SECOND', 10000);
define('MAX_LAST_QUESTIONS_TO_SHUFFLE_FROM_IN_GET_QUESTIONS', 1000);
define('MAX_QUESTIONS_IN_GET_QUESTIONS', 1);

// коды ошибок

define('InsufficientInputParameters', 201);
define('InvalidParameter', 202);
define('UserNotFound',400);
define('TooManyRequests',427);
define('SaveFailed',430);
define('ChatClosed',444);
define('UnderDevelopment',666);