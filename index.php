<?php
session_start();
include_once __DIR__.'/vendor/autoload.php';

use Pecee\Http\Middleware\Exceptions\TokenMismatchException;
use Pecee\SimpleRouter\SimpleRouter as Router;

require_once __DIR__.'/helpers.php';
require_once __DIR__.'/routes/web.php';

try {
    Router::setDefaultNamespace('App\Http\Controllers');
    Router::start();
} catch (TokenMismatchException | \Pecee\SimpleRouter\Exceptions\HttpException | Exception $e) {
    throw new RuntimeException("Something went wrong in routing. ". $e->getMessage());
}
