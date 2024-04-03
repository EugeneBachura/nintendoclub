<?php

namespace App\Console\Commands;

use App\Models\News;
use Illuminate\Console\Command;

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
        // Уменьшение популярности всех новостей на 10 единиц, но не менее нуля
        News::where('popularity', '>=', 10)
            ->decrement('popularity', 10); // уменьшаем на 10

        // Если популярность меньше 10, то устанавливаем её в ноль
        News::where('popularity', '<', 10)
            ->update(['popularity' => 0]);
    }
}
