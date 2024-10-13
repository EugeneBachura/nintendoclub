<?php

namespace App\Services;

class KeywordGenerator
{
    /**
     * Генерирует ключевые слова на основе текста.
     *
     * @param string $content
     * @param string $language
     * @param int $limit
     * @return string
     */
    public function generate(string $content, string $language = 'en', int $limit = 7): string
    {
        // Удаляем HTML-теги и специальные символы
        $cleanedContent = strip_tags($content);
        $cleanedContent = mb_strtolower($cleanedContent);

        // Удаляем знаки пунктуации и специальные символы
        $cleanedContent = preg_replace('/[^\p{L}\s]+/u', '', $cleanedContent);

        // Разбиваем на слова
        $words = preg_split('/\s+/', $cleanedContent, -1, PREG_SPLIT_NO_EMPTY);

        // Удаляем стоп-слова
        $stopWords = $this->getStopWords($language);
        $keywords = array_diff($words, $stopWords);

        // Подсчитываем частоту слов
        $frequency = array_count_values($keywords);

        // Сортируем по убыванию частоты
        arsort($frequency);

        // Получаем топ ключевых слов
        $topKeywords = array_slice(array_keys($frequency), 0, $limit);

        return implode(', ', $topKeywords);
    }

    /**
     * Возвращает массив стоп-слов для указанного языка.
     *
     * @param string $language
     * @return array
     */
    private function getStopWords(string $language): array
    {
        $stopWords = [];

        switch ($language) {
            case 'ru':
                $stopWords = [
                    'и',
                    'в',
                    'не',
                    'на',
                    'что',
                    'я',
                    'с',
                    'со',
                    'как',
                    'а',
                    'то',
                    'все',
                    'она',
                    'так',
                    'его',
                    'но',
                    'да',
                    'ты',
                    'к',
                    'у',
                    'же',
                    'вы',
                    'за',
                    'бы',
                    'по',
                    'только',
                    'ее',
                    'мне',
                    'было',
                    'вот',
                    'от',
                    'меня',
                    'еще',
                    'нет',
                    'о',
                    'из',
                    'ему',
                    'теперь',
                    'когда',
                    'даже',
                    'ну',
                    'вдруг',
                    'ли',
                    'если',
                    'уже',
                    'или',
                    'ни',
                    'быть',
                    'был',
                    'него',
                    'до',
                    'вас',
                    'нибудь',
                    'опять',
                    'уж',
                    'вам',
                    'ведь',
                    'там',
                    'потом',
                    'себя',
                    'ничего',
                    'ей',
                    'может',
                    'они',
                    'тут',
                    'где',
                    'есть',
                    'надо',
                    'ней',
                    'для',
                    'мы',
                    'тебя',
                    'их',
                    'чем',
                    'была',
                    'сам',
                    'чтоб',
                    'без',
                    'будто',
                    'чего',
                    'раз',
                    'тоже',
                    'себе',
                    'под',
                    'будет',
                    'ж',
                    'тогда',
                    'кто',
                    'этот'
                ];
                break;
            case 'pl':
                $stopWords = [
                    'i',
                    'w',
                    'nie',
                    'na',
                    'co',
                    'ja',
                    'z',
                    'że',
                    'to',
                    'a',
                    'się',
                    'o',
                    'ale',
                    'jak',
                    'jest',
                    'tak',
                    'do',
                    'ty',
                    'już',
                    'czy',
                    'by',
                    'tylko',
                    'po',
                    'dla',
                    'mnie',
                    'mój',
                    'mi',
                    'jestem',
                    'ma',
                    'tego',
                    'nim',
                    'tam',
                    'wszystko',
                    'są',
                    'czyli',
                    'mamy',
                    'teraz',
                    'dlaczego',
                    'może',
                    'przez',
                    'go',
                    'też',
                    'bardziej',
                    'jej',
                    'niż',
                    'być',
                    'pan',
                    'pani',
                    'dlatego',
                    'kiedy',
                    'nawet',
                    'kto',
                    'gdzie',
                    'jeszcze',
                    'tych'
                ];
                break;
            default:
                // Английские стоп-слова по умолчанию
                $stopWords = [
                    'the',
                    'and',
                    'is',
                    'in',
                    'at',
                    'of',
                    'a',
                    'to',
                    'it',
                    'that',
                    'i',
                    'this',
                    'for',
                    'you',
                    'was',
                    'with',
                    'on',
                    'as',
                    'but',
                    'be',
                    'they',
                    'he',
                    'she',
                    'or',
                    'which',
                    'we',
                    'an',
                    'by',
                    'do',
                    'not',
                    'are',
                    'from',
                    'his',
                    'her',
                    'have',
                    'all',
                    'my',
                    'so',
                    'me',
                    'up',
                    'one',
                    'about',
                    'who',
                    'what',
                    'when',
                    'where',
                    'can',
                    'if',
                    'would',
                    'there',
                    'their'
                ];
                break;
        }

        return $stopWords;
    }
}
