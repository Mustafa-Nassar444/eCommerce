<?php
function lang($phrase)
{
    static $lang = [
        "HOME_ADMIN" => "Home",
        "CATEGORIES" => "Categories",
        "ITEMS" => "Items",
        "MEMBERS" => "Members",
        "COMMENTS" => "Comments",
        "STATISTICS" => "Statistics",
        "LOGS" => "Logs"


    ];

    return $lang[$phrase];
}
