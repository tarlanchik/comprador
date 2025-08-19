<?php

use App\Http\Controllers\Api\CommentController;

Route::middleware('auth:sanctum')->group(function () {
    // Comment routes
    Route::get('/news/{news}/comments', [\App\Http\Controllers\Admin\CommentController::class, 'index']);
    Route::post('/news/{news}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    Route::post('/comments/{comment}/like', [CommentController::class, 'like']);
    Route::post('/comments/{comment}/report', [CommentController::class, 'report']);
});
