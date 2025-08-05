<?php

/* It's loading all the files in the same directory as the main file. */
foreach (glob("Config/*.php") as $filename) {
    require_once $filename;
}
/* It's loading all the files in the same directory as the main file. */
foreach (glob("Class/*.php") as $filename) {
    require_once $filename;
}
/* It's loading all the files in the same directory as the main file. */
foreach (glob("Functions/*.php") as $filename) {
    require_once $filename;
}




