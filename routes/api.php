<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationController;

Route::post('/organizations', [OrganizationController::class, 'storeOrganizationWithRelations']);
Route::get('/organizations/{name}', [OrganizationController::class, 'getOrganizationRelations']);

