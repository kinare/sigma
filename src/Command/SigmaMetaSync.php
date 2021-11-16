<?php


namespace KTL\Sigma\Command;


use Illuminate\Console\Command;
use KTL\Sigma\Http\Controllers\SigmaController;

class SigmaMetaSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sigma:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sigma Configs sync';

    protected $sigmaController;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sigmaController = new SigmaController();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->sigmaController->refresh();
    }
}
