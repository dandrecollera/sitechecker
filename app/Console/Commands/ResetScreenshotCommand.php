<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ResetScreenshotCommand extends Command
{
    protected $signature = 'screenshot:reset {id}';

    protected $description = 'Reset screenshot for a site';

    public function handle()
    {
        $id = $this->argument('id');

        $site = DB::table('sitelist')->where('id', $id)->first();

        if (!$site) {
            $this->error("Site with ID $id not found.");
            return;
        }

        $url = escapeshellarg($site->url);
        $scriptPath = base_path('node_scripts/screenshot.js');
        $filename = 'screenshots/' . md5($site->url) . '_' . now()->format('YmdHis') . '.png';
        $storagePath = storage_path('app/public/' . $filename);

        $directory = dirname($storagePath);
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $command = "node $scriptPath $url $storagePath";

        Log::info("Executing command: $command");

        exec($command, $output, $returnVar);

        Log::info("Return Value: $returnVar");
        Log::info($output);

        if ($returnVar === 0) {
            $publicPath = Storage::url($filename);

            DB::table('sitelist')->where('id', $id)->update([
                'screenshot' => $publicPath,
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

            DB::table('imagehistory')->insert([
                'siteid' => $id,
                'screenshot' => $publicPath,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

            $this->info("Screenshot reset successfully for site with ID $id.");
        } else {
            Log::error("Error: Unable to take screenshot. Output: " . implode("\n", $output));
            $this->error("Failed to reset screenshot for site with ID $id.");
        }
    }
}
