<?php
/**
 * Created by PhpStorm.
 * Author: Petr Marochkin (petun911@gmail.com)
 * Date: 17.06.16
 * Time: 2:00
 */

use Petun\Reminders\Handler;

require_once "vendor/autoload.php";

$b = new Handler();
$b->handleRequest();