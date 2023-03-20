<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PublishPWA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:publish-pwa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Service Worker|Offline HTMl|manifest file for PWA application.';

    public $composer;

    public function __construct()
    {
        parent::__construct();

        $this->composer = app()['composer'];
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $publicDir = public_path();
        
        $manifestTemplate = file_get_contents(__DIR__.'/../../../resources/stubs/manifest.stub');
        $this->createFile($publicDir. DIRECTORY_SEPARATOR, 'manifest.json', $manifestTemplate);
        $this->info('manifest.json file is published.');
        
        $offlineHtmlTemplate = file_get_contents(__DIR__.'/../../../resources/stubs/offline.stub');
        $this->createFile($publicDir. DIRECTORY_SEPARATOR, 'offline.html', $offlineHtmlTemplate);
        $this->info('offline.html file is published.');     
        
        $swTemplate = file_get_contents(__DIR__.'/../../../resources/stubs/sw.stub');
        $this->createFile($publicDir. DIRECTORY_SEPARATOR, 'sw.js', $swTemplate);
        $this->info('sw.js (Service Worker) file is published.');     

        $this->info('Generating autoload files');
        $this->composer->dumpOptimized();

        $this->info('Greeting!.. Enjoy PWA site...');
    }

    public static function createFile($path, $fileName, $contents)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $path = $path.$fileName;

        file_put_contents($path, $contents);
    }
}
