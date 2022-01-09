<?php

use App\Controllers\ExtensionAndModuleController;
use App\Controllers\PaymentCheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContentDataController;
use App\Http\Controllers\InformationController;

use Pecee\SimpleRouter\SimpleRouter as Router;


Router::get('/', [HomeController::class, 'home'])->name('home');

Router::get('/version/{get_version?}', [ContentDataController::class, 'show'])
    ->where(['get_version', '[A-Za-z]+']);

Router::post('/save_configuration', [ContentDataController::class, 'save_configuration']);
Router::post('/new-input-field', [ContentDataController::class, 'new_input_field']);


/**
 * Generate data
 */
Router::post('/generate_data', [App\Controllers\generator\ExportData::class, 'generateModule']);
Router::get('/information', [InformationController::class, 'show']);

/**
 * Download Module File
 */
Router::post('/download', function () {

    $getName     = $_POST['download'];
    $downloadDir = 'app/public/migrations/download/';

    header('Content-disposition: attachment; filename='.$getName.'');
    header('Content-type: application/zip');
    readfile($downloadDir.$getName);
    exit();

});

Router::error(function() {
    response()->redirect('/');
});
