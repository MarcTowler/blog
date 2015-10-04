<?php
if($_SERVER['REMOTE_ADDR'] != '' && (($_SERVER['REMOTE_ADDR'] == '207.97.227.253') || ($_SERVER['REMOTE_ADDR'] == '50.57.128.197') || ($_SERVER['REMOTE_ADDR'] == '108.171.174.178')))
{
    `git pull`;
} else {
    header('Location: index.php', , 404);
}