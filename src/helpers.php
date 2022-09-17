<?php

use Symfony\Component\Dotenv\Dotenv;

function env($key){
    $dotenv = new Dotenv();
    $dotenv->usePutenv(true)->bootEnv(__DIR__.'/../.env');

    return getenv($key);
}