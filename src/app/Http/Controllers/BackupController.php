<?php

namespace Backpack\BackupManager\app\Http\Controllers;

use Artisan;
use Exception;
use Illuminate\Routing\Controller;
use Log;
use Request;
use Response;
use Storage;


class BackupController extends Controller
{
    public function index()
    {
        if (!count(config('backup.backup.destination.disks'))) {
            dd(trans('backpack::backup.no_disks_configured'));
        }

        $this->data['backups'] = [];

        foreach (config('backup.backup.destination.disks') as $disk_name) {
            $disk = Storage::disk($disk_name);

            $files = $disk->allFiles();

            // make an array of backup files, with their filesize and creation date
            foreach ($files as $k => $f) {
                // only take the zip files into account
                if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                    $this->data['backups'][] = [
                        'file_path'     => $f,
                        'file_name'     => str_replace('backups/', '', $f),
                        'file_size'     => $disk->size($f),
                        'last_modified' => $disk->lastModified($f),
                        'disk'          => $disk_name,
                        'download'      => is_a($disk->getAdapter(), 'League\Flysystem\Local\LocalFilesystemAdapter', true),
                    ];
                }
            }
        }

        // reverse the backups, so the newest one would be on top
        $this->data['backups'] = array_reverse($this->data['backups']);
        $this->data['title'] = 'Backups';

        return view('backupmanager::backup', $this->data);
    }

    public function create()
    {
        $message = 'success';

        $command = config('backpack.backupmanager.artisan_command_on_button_click') ?? 'backup:run';

        $flags = $command === 'backup:run' ? config('backup.backpack_flags', []) : [];

        try {
            foreach (config('backpack.backupmanager.ini_settings', []) as $setting => $value) {
                ini_set($setting, $value);
            }

            Log::info('Backpack\BackupManager -- Called backup:run from admin interface');

            Artisan::call($command, $flags);

            $output = Artisan::output();
            if (strpos($output, 'Backup failed because')) {
                preg_match('/Backup failed because(.*?)$/ms', $output, $match);
                $message = "Backpack\BackupManager -- backup process failed because ";
                $message .= isset($match[1]) ? $match[1] : '';
                Log::error($message.PHP_EOL.$output);
            } else {
                Log::info("Backpack\BackupManager -- backup process has started");
            }
        } catch (Exception $e) {
            Log::error($e);

            return Response::make($e->getMessage(), 500);
        }

        return $message;
    }

    /**
     * Downloads a backup zip file.
     */
    public function download()
    {
        $diskName = Request::input('disk');

        if(! in_array($diskName, config('backup.backup.destination.disks'))) {
            abort(500, trans('backpack::backup.unknown_disk'));
        }

        $disk = Storage::disk($diskName);
        $fileName = Request::input('file_name');

        if(! is_a($disk->getAdapter(), 'League\Flysystem\Local\LocalFilesystemAdapter', true)) {
            abort(404, trans('backpack::backup.only_local_downloads_supported'));
        }

        if(! $disk->exists($fileName)) {
            abort(404, trans('backpack::backup.backup_doesnt_exist'));
        }

        return $disk->download($fileName);  
    }

    /**
     * Deletes a backup file.
     */
    public function delete($file_name)
    {
        $disk = Storage::disk(Request::input('disk'));

        if ($disk->exists($file_name)) {
            $disk->delete($file_name);

            return 'success';
        } else {
            abort(404, trans('backpack::backup.backup_doesnt_exist'));
        }
    }
}
