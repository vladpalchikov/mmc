<?php

namespace MMC\Console\Commands;

use Illuminate\Console\Command;
use MMC\Models\User;
use MMC\Models\UserMMC;

class ConvertMMC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmc:convert-mmc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Конвертация привязки ММЦ в новый формат';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (User::all() as $user) {
            if (UserMMC::where('user_id', $user->id)->count() == 0) {
                $this->info($user->name);
                $userMMC = new UserMMC;
                $userMMC->user_id = $user->id;
                if ($user->mmc_id) {
                    $userMMC->mmc_id = $user->mmc_id;
                } else {
                    $userMMC->mmc_id = 0;
                }
                $userMMC->save();
            }
        }
    }
}
