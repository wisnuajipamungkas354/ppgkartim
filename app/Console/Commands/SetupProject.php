<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Untuk menjalankan beberapa command agar project mudah di setup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memula setup project...');

        $this->info('Membuat database...');
        $this->call('migrate:fresh', ['--seed' => true]);

        $this->info('Membuat list roles...');
        $this->call('shield:generate', ['--all' => true]);

        $this->info('Membuat Super Admin');
        $this->call('shield:super-admin');

        $this->info('Setup Selesai!');
        return Command::SUCCESS;
    }
}
