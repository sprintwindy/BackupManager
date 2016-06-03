<?php

Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth']], function () {

    // Backup
    Route::get('backup', 'BackupController@index');
    Route::put('backup/create', 'BackupController@create');
    Route::get('backup/download/{folder_name}/{file_name?}', 'BackupController@download');
	Route::delete('backup/delete/{folder_name}/{file_name?}', 'BackupController@delete');
});
