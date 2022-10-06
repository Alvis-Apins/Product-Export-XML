<?php

namespace App;

use DateTime;
use DOMDocument;
use DOMException;
use Exception;

class helperFunctions
{
    public static function sortLanguageContent(string $content_1, string $language_id_1, string $content_2, string $language_id_2, string $content_3, string $language_id_3): array
    {
        $content = array_fill(1,3,' ');
        $content[$language_id_1] = $content_1;
        $content[$language_id_2] = $content_2;
        $content[$language_id_3] = $content_3;

        return $content;
    }

    /**
     * @throws DOMException
     */
    public static function setLanguageContent(DOMDocument $dom, array $descriptions, $description):void {
        $description_lv = $dom->createElement('lv', $descriptions[1]);
        $description->appendChild($description_lv);

        $description_en = $dom->createElement('en', $descriptions[2]);
        $description->appendChild($description_en);

        $description_ru = $dom->createElement('ru', $descriptions[3]);
        $description->appendChild($description_ru);
    }

    /**
     * @throws Exception
     */
    public static function checkSpecials($price, $start_date, $end_date):?float {
        $today = new DateTime(date('Y-m-d'));
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);

        if ($today >= $start && $today <= $end) return ($price + ($price * (float)$_ENV['PVN']));
        return null;
    }

}