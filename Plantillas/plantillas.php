<?php
return [
'vSiesta_gateway' => 
"<b><a href='t.me/SiestaChkBot'>[‣]</a> Gateway ➤ %s</b> 
━━━━━━━━━━━━━
<b><a href='t.me/SiestaChkBot'>[✞]</a> Card:</b><code> %s</code>
<b><a href='t.me/SiestaChkBot'>[✞]</a> Status:</b><i> Checking your card...</i>",

'tool_fake'=> "
<b><i><u>SiestaTools</u></i></b> ➳ <code>Address Gen</code>
━━ ━ ━ ━ ━ ━ ━ ━ ━━
<b><i>First Name </i></b> ➳ <code>%s</code>
<b><i>Last Name </i></b> ➳ <code>%s</code>
<b><i>Street</i></b> ➳ <code>%s</code>
<b><i>City</i></b> ➳ <code>%s</code>
<b><i>State/province</i></b> ➳ <code>%s</code>
<b><i>Zip Code</i></b> ➳ <code>%s</code>
<b><i>Phone Number</i></b> ➳ <code>%s</code>
<b><i>Country</i></b> ➳ <code>%s</code>
<b><i>Username</i></b> ➳ <code>%s</code>
<b><i>Password</i></b> ➳ <code>%s</code>",

'tool_fakeUsage'=>'
<b><u>SiestaChk Fake Generator</u></b> [⚡]
<b>Usage:</b> <code>%s</code> 
<i>Examples:</i>
<code>%s</code>
<code>%s</code>',

'tool_sites'=>'
<b><u>SiestaChk 
Site Verificator</u></b> [⚡]
<b>Usage:</b> <code>%s</code> 
<i>Examples:</i>
<code>%s</code>
<code>%s</code>',

'bin_format' => '
<b><u>SiestaChk Bin Validator</u></b> [⚡]
<b>Usage:</b> <code>%s</code>
<i>Examples:</i>
<code>%s</code>
<code>%s</code>',

'Bin' => "
<b>[ϟ] Bin Lookup
━━━━━━━━━━━━━━━
[Ϟ] <i>Bin:</i> <code>%s</code>
[Ϟ] <i>Info:</i> %s - %s - %s
[Ϟ] <i>Bank:</i> %s
[Ϟ] <i>Country:</i> %s (%s) %s
━━━━━━━━━━━━━━━
Checked By: <a href='tg://user?id=%s'>%s</a> %s
[Ϟ] <i>Response Time:</i> <code>%ss</code>
</b>",



'tool_loading'=>"<code>Generating Your Addresses in Progress..⚡</code>",
'site_loading'=>"<code>Your site in Progress..⚡</code>",
'bin_loading'=>"<code>Your bin in Progress..⚡</code>",

'gateway'=>"
<b>Siesta Gate ~> %s</b>  
————————————————
[ァ]<b> Card -></b> <code>%s</code>
[ァ]<b> Status -></b> <i>%s</i>
[ァ]<b> Response -></b> <i>%s</i>
————————————————
[ァ]<b> Info:</b> <i>%s</i>
[ァ]<b> Bank:</b> <i>%s</i>
[ァ]<b> Country:</b> <i>%s</i>
————————————————
T ~ %s/s | P ~ Live! ❇️ | R ~ 0
Checked by ~ %s
————————————————
<code>Dev: @Fateeth</code>",

'restricted'=> "<code>\$_Restricted Area</code> 🚫\r\n————————————————\r\n<b><i>_hey! This feature is only for higher-tier members. Curious about upgrading? Type <code>/discoverplans en|es</code> to find out more.</i></b>\r\n————————————————\r\n<b>[⚠️] vSiesta~</b><i> be yourself don't let others tell you who you are.</i>",

'buttons_gateways' => [
    [
        ['text' => 'Channel 🧿', 'url' => "https://t.me/SiestaChk"],
        ['text' => 'Chat 🧿', 'url' => "https://t.me/SiestaChkChat"],
    ],
    ],

'restricted_gateway'=>"
<b>This Gate ⚠️</b>
<b>——————————</b>
<b>↯ Status:</b><code> OFF!❌</code>
<b>↯ Reason:</b> <code>Maintenance </code>
<b>↯ Gateway: </b> <code>%s</code>
<b>——————————</b>",

'not_authorized' => '❌ No estás autorizado para usar este comando.',


'invalid_key_format' => 'El formato no es correcto. Usa `/key P|30` para un plan Premium de 30 días.',

'key_created' => "
<b><i>Siesta ~></i></b> <code>Key Generator</code>
━━━━━━━━━━━━━━
<b><i>Key ></i></b> %s
━━━━━━━━━━━━━━
<b><i>Plan ></i></b> <code>Premium</code>
<b><i>Days ></i></b> <code>%s</code>
<b><i>Expirity ></i></b> <code>%s</code>
━━━━━━━━━━━━━━
<b><i>Usage</i></b><code> %s</code>
━━━━━━━━━━━━━━",

'key_claim' => "
<b><i>Siesta Tool ~></i></b> <code>Claim Membership Key</code>
━━━━━━━━━━━━━━
<b><i>Status ></i></b> <code>Claimed! ❇️</code>
<b><i>Key ></i></b> <code>%s</code>
<b><i>Plan ></i></b> <code>%s</code>
<b><i>Days ></i></b> <code>%s</code>
<b><i>Expiry ></i></b> <code>%s</code>
━━━━━━━━━━━━━━
<b><i>Claimed By ~></i></b> <code>%s</code>
",

];

