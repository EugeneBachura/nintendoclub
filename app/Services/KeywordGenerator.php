<?php

namespace App\Services;

use TextAnalysis\Analysis\FreqDist;
use TextAnalysis\Filters\StopWordsFilter;

class KeywordGenerator
{
    /**
     * Генерирует ключевые слова на основе текста
     *
     * @param string $content
     * @param string $language
     * @param int $limit
     * @return string
     */
    public function generate(string $content, string $language = 'en', int $limit = 10): string
    {
        // Убираем HTML-теги и специальные символы
        $cleanedContent = strip_tags($content);

        // Приводим текст к нижнему регистру
        $cleanedContent = mb_strtolower($cleanedContent);

        // Разбиваем текст на токены (слова) с использованием встроенных функций PHP
        $tokens = preg_split('/\s+/', $cleanedContent, -1, PREG_SPLIT_NO_EMPTY);

        // Удаление стоп-слов
        $stopWords = $this->getStopWords($language);
        $tokens = array_filter($tokens, function ($token) use ($stopWords) {
            return !in_array($token, $stopWords) && preg_match('/^\p{L}+$/u', $token);
        });

        // Получение частоты встречаемости слов
        $freqDist = new FreqDist($tokens);
        $mostFrequent = $freqDist->getKeyValuesByFrequency();
        arsort($mostFrequent);

        // Извлечение топовых ключевых слов
        $topKeywords = array_slice(array_keys($mostFrequent), 0, $limit);

        // Преобразование массива ключевых слов в строку
        return implode(', ', $topKeywords);
    }

    /**
     * Получает список стоп-слов для заданного языка
     *
     * @param string $language
     * @return array
     */
    private function getStopWords(string $language): array
    {
        $stopWords = [];

        switch ($language) {
            case 'ru':
                $stopWords = ['и', 'в', 'не', 'на', 'что', 'я', 'с', 'со', 'как', 'а', 'то'];
                break;
            case 'pl':
                $stopWords = ['i', 'w', 'nie', 'na', 'co', 'ja', 'z', 'że', 'to', 'a'];
                break;
            default:
                // Английские стоп-слова по умолчанию
                $stopWords = ['the', 'and', 'is', 'in', 'at', 'of', 'a', 'to', 'it', 'that'];
                break;
        }

        return $stopWords;
    }
}
