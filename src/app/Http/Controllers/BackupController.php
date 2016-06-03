<?php

namespace Backpack\BackupManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Artisan;
use Log;
use Storage;

class BackupController extends Controller
{
    public function index()
    {
        $disk = Storage::disk(config('laravel-backup.backup.destination.disks')[0]);

        $files = $disk->allFiles();
        $this->data['backups'] = [];

        // make an array of backup files, with their filesize and creation date
        foreach ($files as $k => $f) {
            // only take the zip files into account
            if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                $this->data['backups'][] = [
                                            'file_path'     => $f,
                                            'file_name'     => str_replace('backups/', '', $f),
                                            'file_size'     => $disk->size($f),
                                            'last_modified' => $disk->lastModified($f),
                                            ];
            }
        }

        // reverse the backups, so the newest one would be on top
        $this->data['backups'] = array_reverse($this->data['backups']);
        $this->data['title'] = 'Backups';

        return view('backupmanager::backup', $this->data);
    }

    public function create()
    {
        try {
            // start the backup process
          Artisan::call('backup:run');
            $output = Artisan::output();

          // log the results
          Log::info("Backpack\BackupManager -- new backup started from admin interface \r\n".$output);
          // return the results as a response to the ajax call
          echo $output;
        } catch (Exception $e) {
            Response::make($e->getMessage(), 500);
        }

        // return 'success';
    }

    /**
     * Downloads a backup zip file.
     *
     * TODO: make it work no matter the flysystem driver (S3 Bucket, etc).
     */
    public function download($folder_name, $file_name)
    {
        $disk = Storage::disk(config('laravel-backup.backup.destination.disks')[0]);

        if ($disk->exists($folder_name.'/'.$file_name)) {
            return response()->download(storage_path('backups/'.$folder_name.'/'.$file_name));
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }

    /**
     * Deletes a backup file.
     */
    public function delete($folder_name, $file_name)
    {
        $disk = Storage::disk(config('laravel-backup.backup.destination.disks')[0]);

        if ($disk->exists($folder_name.'/'.$file_name)) {
            $disk->delete($folder_name.'/'.$file_name);

            return 'success';
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }
}
