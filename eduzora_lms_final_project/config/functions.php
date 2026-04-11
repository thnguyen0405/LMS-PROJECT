<?php
// 0:24
// 3:43
// 6:34:23
function convert_duration_to_seconds($duration) {
    $parts = explode(":", $duration);
    $total_seconds = 0;
    $num_parts = count($parts);
    if ($num_parts == 2) {
        $minutes = (int)$parts[0];
        $seconds = (int)$parts[1];
        $total_seconds = ($minutes * 60) + $seconds;
    } elseif ($num_parts == 3) {
        $hours = (int)$parts[0];
        $minutes = (int)$parts[1];
        $seconds = (int)$parts[2];
        $total_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;
    } else {
        return "Invalid time format.";
    }
    return $total_seconds;
}


function convert_seconds_to_minutes_hours($seconds) {
    if (!is_numeric($seconds) || $seconds < 0) {
        return "Invalid input";
    }
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $seconds = $seconds % 60;
    $time = [];
    if ($hours > 0) {
        $time[] = $hours . "h";
    }
    if ($minutes > 0) {
        $time[] = $minutes . "m";
    }
    if ($seconds > 0 || empty($time)) {
        $time[] = $seconds . "s";
    }
    return implode(" ", $time);
}