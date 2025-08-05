<?php
return [
/////////////////////////////////////[mensajes del bot]/////////////////////////////////////
    'welcome' => "<b> Hello! %s 🍄, Welcome to SiestaChk. Relax and recharge! 
━━━━━━━━━━━━━
Today is:<code> 
%s %s %s</code></b>
━━━━━━━━━━━━━",
   
    'greeting' => "[🇬🇧] <i>%s, ready to use siesta? I'm your personal card checking bot. Use /cmds to see what I can do.</i>",
    
    'status_online' => "<i>✅ SiestaBot is online and ready to assist you.</i>",
   
    
    'extended_welcome' => "[🇬🇧] <b>Hello</b> %s, <i>welcome to</i> SiestaChk! <b>The gates of relaxation are open for you.</b> <i>Explore and enjoy our tools and features designed for your comfort!</i> To know all my commands, use the buttons displayed here:",
    
    'cmds_intro' => "[🇬🇧] <b>These are the commands you can use to get the most out of</b> SiestaChk. <i>Relax and enjoy!</i>",

    'siesta_welcome_gates' => "<b>Welcome to SiestaChk, %s!</b> 
━━━━━━━━━━━━━
<b>🔗 Active gates:</b> %s Gates available
━━━━━━━━━━━━━
<b>🔐 Charged:</b> %s   |   <b>💼 Auth:</b> %s
<b>💳 CCN (Authentication & Processing):</b> %s | <b>🔍 Mass Cheking:</b> %s
━━━━━━━━━━━━━
<i>🌟 Choose your ideal gate and let SiestaChk do the rest. Enjoy a relaxed and efficient experience!</i>",


'sayonara'=>"Goodbye, teacher. It was a pleasure. Thanks for using me :3",

'description' => "🌙 <b>Siesta Description</b>
——————————————————
🛜 <b>Connect with us:</b>
——————————————————
<b>News and Updates:</b> <i>@SiestaChkUpdates</i>
<b>References and Guides:</b> <i>@SiestaChkChannel</i>
<b>Free Users Community:</b> <i>@SiestaChkChat</i>
——————————————————
<b>Developer:</b> <i>@ByteFear</i>
-
⚡️ <i>“Optimized performance. Agile experience. Welcome to Siesta, the easiest way to perform checks effortlessly.”</i>
———————————————————
<b>Team:</b>
-
%s
——————————————————
🚀 <b>Current Siesta Version:</b> <i>%s</i> ⏳ <b>Update:</b> <i>%s</i>
——————————————————
💬 <b>Have a problem?</b>
Contact us at: <i>@SiestaChk</i>
——————————————————
<b>Thanks for using Siesta Chk!</b>
✨ <i>Fast, precise checks at your fingertips.</i>"
,

'vSiesta' => "<i><b>vSiesta &lt;/&gt;</b></i>: <code>Siesta Versions, the evolution has begun</code>\n————————————————\n⚔️ <i><b>Siesta Versions: The ultimate evolution of all previous bots, surpassing the legacy of Asuna.</b> This is the most refined and powerful version yet, designed to offer unmatched performance and innovation.</i>\n———————————————\n<i><b>[🇬🇧] This version redefines innovation.</b> Everything you knew has been improved, setting a new standard in the world of bots.</i>\n———————————————\n<i>✅ The future is here. Are you ready to discover what vSiesta has in store for you?</i>",
'vLenguaje' => "<i>vSiesta securely and stably stores the language you use to offer you a personalized and smooth experience.\n——————————————————\n Select your language </i>",
// Inglés
'updateMessage' => [ 
    'no_updates' => "<b>vSiesta | Today's Updates</b>\n━━━━━━━━━━━━━━\n<i>No new updates available today. Stay tuned for upcoming versions.</i> ✨\n━━━━━━━━━━━━━━",
    'recent_updates' => "<b>vSiesta | Recent Updates</b>\n━━━━━━━━━━━━━━\n<i>Version:</i> <code>%s</code>\n<i>Date:</i> <code>%s</code>\n<i>Description:</i> %s\n━━━━━━━━━━━━━━"
],
'rewardMessage' => [
    'no_rewards' => "<b>vSiesta | Today's Rewards</b>\n━━━━━━━━━━━━━━\n<i>No new rewards available today. Come back later to check out future rewards.</i> ✨\n━━━━━━━━━━━━━━",
    'available_rewards' => "<b>vSiesta | Available Rewards</b>\n━━━━━━━━━━━━━━\n<i>Title:</i> <code>%s</code>\n<i>Availability Date:</i> <code>%s</code>\n<i>Description:</i> %s\n━━━━━━━━━━━━━━"
],

'vSiesta_user' => [
    "vSiesta| <b>User Information</b>\n━━━━━━━━━━━━\n- <i>User</i>: <code>%s</code> | <i>Language</i>: <code>%s</code>\n- <i>Antispam</i>: <code>%s</code> | <i>Credits</i>: <code>%s</code>\n- <i>Identifier</i>: <code>%s</code>\n━━━━━━━━━━━━\n- <i>Account Status</i>: <code>%s</code>\n- <i>Days Remaining</i>: <code>%d</code> days"
],

'messages' => [
    'vSiesta_tool_header' => "<u><b>Siesta Tools!</b></u>",
    'vSiesta_tool_entry' => "<code>%s:</code> <b>%s</b>\n<i>Status:</i><b> %s</b>",
    'vSiesta_tool_footer' => "~vSiesta/page1",
    'status_enable' => "Enabled ✅",
    'status_disable' => "Disabled ❌",
],
'messages_gateway' => [
    'gateway_footer' => "<i><b>~vSiesta/page%s</b></i>",
    'gateway_name' => "<b>- Name:</b> %s",
    'gateway_format' => "<b>- Format:</b><code> %s card|month|year|cvv</code>",
    'gateway_status_enabled' => "<b>- Status: enabled ✅</b>",
    'gateway_status_disabled' => "<b>- Status: disabled ❌</b>",
    'gateway_comment' => "<b>- Comment:</b><code> %s </code>"
],

/////////////////////////////////////[Botones]/////////////////////////////////////
'buttons_cmds' => [
    [
        ['text' => 'Auth', 'callback_data' => 'auth'],
        ['text' => 'Charged', 'callback_data' => 'charged'],
        ['text' => 'CCN', 'callback_data' => 'ccn'],
        ['text' => '3D', 'callback_data' => '3d'],
    ],
        [
            ['text' => 'Mass Cheking', 'callback_data' => 'mass'],
            ['text' => 'Special', 'callback_data' => 'Special'],
            ['text' => 'Back', 'callback_data' => 'cmd'],
        ]
        ],

    'buttons' => [
        [
            ['text' => 'Gateways', 'callback_data' => 'gateways'],
            ['text' => 'Tools', 'callback_data' => 'tools'],
            ['text' => 'Description', 'callback_data' => 'descripcion']
        ],
        [
           
            ['text' => 'vSiesta[⚔️]', 'callback_data' => 'siesta'],
            ['text' => 'Close', 'callback_data' => 'cerrar']
        ]
        ],
    'button_tap' => [
        [
            ['text' => 'vSiesta⚔️', 'callback_data' => 'siesta'],
            ['text' => 'Back', 'callback_data' => 'cmd'],
         
       
            ]],
            'button_gate' => 
    [
        ['text' => 'Home', 'callback_data' => 'gateways'],
    ]
,


'buttons_vSiesta' => [
    [
        ['text' => "Updates", 'callback_data' => "Actualizaciones"],
        ['text' => "Language", 'callback_data' => "lenguaje"],
        ['text' => "Rewards", 'callback_data' => "recompensas"],
    ],
    [
        ['text' => "Account", 'callback_data' => "account_info"],
        ['text' => "Back", 'callback_data' => "cmd"],
    ],
],


'buttons_lag' => [[
    ['text' => 'Español 🇪🇸', 'callback_data' => 'langcmd_es'],
    ['text' => 'English 🇬🇧', 'callback_data' => 'langcmd_en']
],
[
    ['text' => 'Français 🇫🇷', 'callback_data' => 'langcmd_fr'],
    ['text' => 'Italiano 🇮🇹', 'callback_data' => 'langcmd_it']
],
[
    ['text' => 'Deutsch 🇩🇪', 'callback_data' => 'langcmd_de'],
    ['text' => 'Português 🇵🇹', 'callback_data' => 'langcmd_pt'],
   
    
],
[ ['text' => 'Back', 'callback_data' => 'siesta']
]],
        'buttons_start' => [ 
            
            [
               ['text' => "Propietario", 'url' => "https://t.me/ByteFear"],
               ['text' => "Canal", 'url' => "https://t.me/SiestaChk"],
            ],
            ],
            'message_footer' => "¡¡Generate your own menu, don't use someone else's!!",
        ];
        
