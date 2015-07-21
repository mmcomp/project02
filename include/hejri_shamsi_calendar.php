<?php
date_default_timezone_set("Asia/Tehran");
function convert_date($time)
{
    $weekdays = array("شنبه", "یکشنبه", "دوشنبه", "سه شنبه", "چهارشنبه", "پنج شنبه", "جمعه");
    $months = array("فروردین", "اردیبهست", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند");
    $day_number = date("d", $time);
    $month_number = date("m", $time);
    $year = date("Y", $time);
    $week_day_number = date("w", $time);
    $hour = date("G", $time);
    $minute = date("i", $time);
    $second = date("s", $time);
    switch ($month_number) {
        case 1:
            ($day_number < 20) ? ($month_number = 10) : ($month_number = 11);
            ($day_number < 20) ? ($day_number += 10) : ($day_number -= 19);
            break;
        case 2:
            ($day_number < 19) ? ($month_number = 11) : ($month_number = 12);
            ($day_number < 19) ? ($day_number += 12) : ($day_number -= 18);
            break;
        case 3:
            ($day_number < 21) ? ($month_number = 12) : ($month_number = 1);
            ($day_number < 21) ? ($day_number += 10) : ($day_number -= 20);
            break;
        case 4:
            ($day_number < 21) ? ($month_number = 1) : ($month_number = 2);
            ($day_number < 21) ? ($day_number += 11) : ($day_number -= 20);
            break;
        case 5:
        case 6:
            ($day_number < 22) ? ($month_number -= 3) : ($month_number -= 2);
            ($day_number < 22) ? ($day_number += 10) : ($day_number -= 21);
            break;
        case 7:
        case 8:
        case 9:
            ($day_number < 23) ? ($month_number -= 3) : ($month_number -= 2);
            ($day_number < 23) ? ($day_number += 9) : ($day_number -= 22);
            break;
        case 10:
            ($day_number < 23) ? ($month_number = 7) : ($month_number = 8);
            ($day_number < 23) ? ($day_number += 8) : ($day_number -= 22);
            break;
        case 11:
        case 12:
            ($day_number < 22) ? ($month_number -= 3) : ($month_number -= 2);
            ($day_number < 22) ? ($day_number += 9) : ($day_number -= 21);
            break;
    }
    $new_date["day"] = $day_number;
    $new_date["month_num"] = $month_number;
    $new_date["month_name"] = $months[$month_number - 1];
    if (($month_number < 3) or (($month_number == 3) and ($day_number < 21)))
        $new_date['year'] = $year - 622;
    else
        $new_date['year'] = $year - 621;
    if ($week_day_number == 6)
        $new_date['weekday_num'] = 0;
    else
        $new_date["weekday_num"] = $week_day_number + 1;
    $new_date["weekday_name"] = $weekdays[$new_date['weekday_num']];
    $new_date["hour"] = $hour;
    $new_date["minute"] = $minute;
    $new_date["second"] = $second;
    return $new_date;
}