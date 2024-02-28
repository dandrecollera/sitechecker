<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FunctionController extends Controller
{
    public function index(Request $request){
        $data = array();
        $query = $request->query();


        $data['errors'] = array(
            1 => ['Error: Enter a valid Title', 'danger'],
            2 => ['Error: Enter a valid Web URL', 'danger'],
            3 => ['Error: Duplicated URL', 'danger'],
        );
        if(!empty($query['err'])){
            $data['err'] = $query['err'];
        }

        $data['notifs'] = array(
            1 => ['Website added successfully', 'info'],
            2 => ['Website modified successfully', 'info'],
            3 => ['Website removed', 'info'],
            4 => ['List Updated', 'info'],
            5 => ['Image Updated', 'info'],
            6 => ['Tracking Updated', 'info'],
            7 => ['Cache Cleared', 'info'],
        );
        if(!empty($query['nt'])){
            $data['nt'] = $query['nt'];
        }

        $data['order'] = array(
            0 => ["Default", 'id'],
            1 => ["Name", 'name'],
            2 => ["Date", 'created_at'],
            3 => ["Status", 'active'],
        );
        $data['or'] = 0;
        if(!empty($query['or'])){
            $data['or'] = $query['or'];
        }

        $data['srt'] = 'asc';
        if(!empty($query['srt'])){
            $data['srt'] = $query['srt'];
        }

        $sites = DB::table('sitelist');

        $sites->orderBy($data['order'][$data['or']][1], $data['srt']);

        $data['sites'] = $sites
            ->get()
            ->toArray();


        return view('home', $data);
    }


    public function addSite(Request $request){
        $input = $request->input();

        if(empty($input['webtitle'])){
            return redirect('?err=1');
            die();
        }

        if (!filter_var($input['weburl'], FILTER_VALIDATE_URL)) {
            return redirect('?err=2');
            die();
        }


        $checkcurrent = DB::table('sitelist')
            ->where('name', $input['webtitle'])
            ->orWhere('url', $input['weburl'])
            ->count();

        if($checkcurrent > 0){
            return redirect('?err=3');
        }

        DB::table('sitelist')
            ->insert([
                'name' => $input['webtitle'],
                'url' => $input['weburl'],
                'wordpress_active' => $input['wordpress'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

        return redirect('?nt=1');
    }

    public function editSite(Request $request){
        $input = $request->input();

        if(empty($input['webtitle'])){
            return redirect('?err=1');
            die();
        }

        if (!filter_var($input['weburl'], FILTER_VALIDATE_URL)) {
            return redirect('?err=2');
            die();
        }

        DB::table('sitelist')
            ->where('id', $input['edit-id'])
            ->update([
                'name' => $input['webtitle'],
                'url' => $input['weburl'],
                'wordpress_active' => $input['wordpress'],
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

        return redirect('?nt=2');
    }

    public function deleteSite(Request $request){
        $query = $request->query();

        DB::table('sitelist')
            ->where('id', $query['id'])
            ->delete();

        return redirect('?nt=3');
    }

    public function checkSiteStatus(Request $request){
        $sites = DB::table('sitelist')->get();
        $reports = '';
        $counter = 0;

        foreach($sites as $site){
            if($site->tracking == 1){
                $counter++;
                try {
                    $res = Http::get($site->url);

                    if($res->successful()){
                        $status = 1;

                        $rawHtml = $res->body();

                        $cleanHtml = $this->removeScriptsAndNonBodyContent($rawHtml);

                        $shortenedHtml = substr($cleanHtml, 0, env('CACHE_COUNT'));

                        $publicpath = $site->screenshot;

                        $changeState = 0;
                        if($shortenedHtml != $site->cache){
                            $changeState = 1;
                            $escapedUrl = escapeshellarg($site->url);
                            $scriptPath = base_path('node_scripts/screenshot.js');
                            $filename = 'screenshots/' . md5($site->url) . '_' . date('YmdHis') . '.png';
                            $storagePath = storage_path('app/public/' . $filename);

                            $directory = dirname($storagePath);
                            if (!file_exists($directory)) {
                                mkdir($directory, 0777, true);
                            }

                            $command = "node $scriptPath $escapedUrl $storagePath";

                            exec($command, $output, $returnvar);

                            Log::info($returnvar);
                            Log::info($output);

                            if($returnvar === 0){
                                $publicpath = Storage::url($filename);
                            } else {
                                $errorMessage = "Failed to generate screenshot for URL: {$site->url}. Error Code: $returnvar";
                                Log::error($errorMessage);

                                $publicpath = asset('img/Untitled-1 cov.png');

                            }

                            DB::table('imagehistory')
                                ->insert([
                                    'siteid' => $site->id,
                                    'screenshot' => $publicpath,
                                    'created_at' => Carbon::now()->toDateTimeString(),
                                    'updated_at' => Carbon::now()->toDateTimeString(),
                                ]);

                            DB::table('cachehistory')
                                ->insert([
                                    'siteid' => $site->id,
                                    'cache' =>$shortenedHtml,
                                    'created_at' => Carbon::now()->toDateTimeString(),
                                    'updated_at' => Carbon::now()->toDateTimeString(),
                                ]);
                        }

                        DB::table('sitelist')
                            ->where('id', $site->id)
                            ->update([
                                'cache' => $shortenedHtml,
                                'screenshot' => $publicpath,
                                'active' => $status,
                                'last_check' => Carbon::now()->toDateTimeString(),
                                'last_change' => $changeState == 1 ? Carbon::now()->toDateTimeString() : $site->last_change,
                                'updated_at' => Carbon::now()->toDateTimeString(),
                            ]);

                        $report = $counter . ". (ONLINE) " . $site->url;
                        if($changeState == 1){
                            $report .= ": Changes have been made";
                        }
                        $reports .= $report . PHP_EOL;
                    } else {
                        throw new \Exception('error fetch');
                    }
                } catch (\Throwable $th) {

                    $updatedowntime = $site->active == 1 ? '1' : '0';

                    $status = 0;
                    $count = $site->downcount;
                    $count += 1;

                    if($site->last_down == null){
                        $date = Carbon::now()->toDateTimeString();
                    } else {
                        $date = $site->last_down;
                    }

                    DB::table('sitelist')
                        ->where('id', $site->id)
                        ->update([
                            'active' => $status,
                            'last_down' => $site->active == 1 ? Carbon::now()->toDateTimeString() : $date,
                            'last_check' => Carbon::now()->toDateTimeString(),
                            'downcount' => $count,
                            'updated_at' => Carbon::now()->toDateTimeString(),
                        ]);

                    $report = $counter . ". (OFFLINE) " . $site->url . " (" . $count . " attempts)";
                    $reports .= $report . PHP_EOL;
                }
            }
        }

        DB::table('cronreport')
            ->insert([
                'report' => $reports,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

        return redirect('?nt=4');
    }

    public function removeScriptsAndNonBodyContent($html){
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);

        $scripts = $doc->getElementsByTagName('script');
        foreach ($scripts as $script) {
            $script->parentNode->removeChild($script);
        }

        $body = $doc->getElementsByTagName('body')->item(0);
        $cleanHtml = $doc->saveHTML($body);

        $stripHtml = strip_tags($cleanHtml);
        $stripHtml = preg_replace('/\s+/', ' ', $stripHtml);
        return $stripHtml;
    }

    public function resetScreenshot(Request $request){
        $query = $request->query();

        $sel = DB::table('sitelist')
            ->where('id', $query['id'])
            ->first();

        $escapedUrl = escapeshellarg($sel->url);
        $scriptPath = base_path('node_scripts/screenshot.js');
        $filename = 'screenshots/' . md5($sel->url) . '_' . date('YmdHis') . '.png';
        $storagePath = storage_path('app/public/' . $filename);

        $directory = dirname($storagePath);
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }


        $command = "node $scriptPath $escapedUrl $storagePath";
        Log::info("Executing command: $command");

        exec($command, $output, $returnvar);
        Log::info("Return Value: $returnvar");
        Log::info("Output: " . implode("\n", $output));

        if($returnvar === 0){
            $publicpath = Storage::url($filename);
        } else {
            Log::error("Error: Unable to take screenshot. Output: " . implode("\n", $output));
            $publicpath = asset('img/Untitled-1 cov.png');
        }

        DB::table('sitelist')
            ->where('id', $query['id'])
            ->update([
                'screenshot' => $publicpath,
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

            DB::table('imagehistory')
            ->insert([
                'siteid' => $query['id'],
                'screenshot' => $publicpath,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

        return redirect('/?nt=5');
    }

    public function changeTracking(Request $request){
        $query = $request->query();

        $state = DB::table('sitelist')
            ->where('id', $query['id'])
            ->first();

        $newstate = $state->tracking == 0 ? 1 : 0;

        $sel = DB::table('sitelist')
            ->where('id', $query['id'])
            ->update([
                'tracking' => $newstate
            ]);

        return redirect('/?nt=6');
    }

    public function clearCache(Request $request){
        $query = $request->query();

        DB::table('sitelist')
            ->where('id', $query['id'])
            ->update([
                'cache' => null,
            ]);

        return redirect('/?nt=7');
    }


    public function reportPage(Request $request){
        return view('report');
    }

    public function execTest(Request $request){

        $execvar = shell_exec('node -v');

        print_r($execvar);

    }
}
