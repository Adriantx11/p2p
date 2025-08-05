<?php

/*
|--------------------------------------------------------------------------
| Bot Token
|--------------------------------------------------------------------------
|
| Change this to your Bot API Token
| It can be obtained from https://telegram.dog/BotFather
|
*/
$config['botToken'] = "YOUR_BOT_TOKEN_HERE";

/*
|--------------------------------------------------------------------------
| Admin User ID
|--------------------------------------------------------------------------
|
| Change this to Admin's Numeric User ID
| ID can be obtained from https://telegram.dog/username_to_id_bot
|
*/
$config['adminID'] = "YOUR_ADMIN_ID_HERE";

/*
|--------------------------------------------------------------------------
| Logs Chat ID
|--------------------------------------------------------------------------
|
| Create a New Channel/Group for logging data
| ID can be obtained from https://telegram.dog/BotFather
|
*/
$config['ChatID'] = "YOUR_CHAT_ID_HERE";

/*
|--------------------------------------------------------------------------
| Logs Channel ID
|--------------------------------------------------------------------------
|
| Create a New Channel/Group for logging data
| ID can be obtained from https://telegram.dog/BotFather
|
*/
$config['logsID'] = "YOUR_LOGS_ID_HERE";

/*
|--------------------------------------------------------------------------
| Timezone
|--------------------------------------------------------------------------
|
| Current timezone for Logging Activities with time
| It can be obtained from http://1min.in/content/international/time-zones
| By Default it's in UTC
|
*/
$config['timeZone'] = "UTC";

/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
| SQLite Database to Store User Data
|
*/
$config['db']['type'] = "sqlite";
$config['db']['path'] = "database/siesta.db";

/*
|--------------------------------------------------------------------------
| Anti-Spam Timer
|--------------------------------------------------------------------------
|
| Anti-Spam Timer to prevent Spammers from Spamming the Checker
| Value is in Seconds. "30" = 30seconds
|
*/
$config['anti_spam_timer'] = "30";

/*
|--------------------------------------------------------------------------
| Capsolver Configuration
|--------------------------------------------------------------------------
| API Key for CAPTCHA solving (Optional)
|
*/
$config['capsolver_key'] = "YOUR_CAPSOLVER_KEY_HERE";

/*
|--------------------------------------------------------------------------
| Debug Mode
|--------------------------------------------------------------------------
| Enable debug mode for development
|
*/
$config['debug'] = false;

/*
|--------------------------------------------------------------------------
| Log Errors
|--------------------------------------------------------------------------
| Enable error logging
|
*/
$config['log_errors'] = true; 