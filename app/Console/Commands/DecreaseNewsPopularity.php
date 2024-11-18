<?php

namespace App\Console\Commands;

use App\Models\News;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DecreaseNewsPopularity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:decrease-popularity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Логируем старт команды
        Log::info("Starting news:decrease-popularity command");

        // Уменьшаем популярность для всех записей, где она больше 0
        $decreased = News::where('popularity', '>', 0)->decrement('popularity', 10);

        // Логируем количество обновлённых записей
        Log::info("News popularity decreased for {$decreased} records.");

        // Устанавливаем популярность в 0, если она меньше 0
        $zeroed = News::where('popularity', '<', 0)->update(['popularity' => 0]);

        // Логируем количество записей, обнулённых популярностью
        Log::info("News popularity set to zero for {$zeroed} records.");
    }
}
