<?php

declare(strict_types=1);
require_once('../vendor/autoload.php');

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;

class SiteParser
{
    /*
     * TODO:
     *  - Сделать формирование расписания по 3 сценариям: сегодня, завтра, неделя
     *  - Сделать перевод для вводимой даты, по типу: сегодня => today, завтра => tomorrow
     */

    public function getSchedule(string $group_name, string $date = ''): string
    {
        $document = new Document(
            'https://uspu.ru/education/eios/schedule/?group_name=' . $group_name,
            true
        );

        $message = "Группа: $group_name\n";

        try {
            $updated = 'updated';
            //$rasp_update = $document->find('.rasp-update')[0]->text();
            $message .= $updated . "\n";

            $rasp_item = $document->find('.rasp-item');
            foreach ($rasp_item as $item) {

                $rasp_week = $item->find('.rasp-week');
                foreach ($rasp_week as $week) {
                    $message .= str_repeat('.', 25) . "\n" . $week->text() . " ";
                }
                $rasp_day = $item->find('.rasp-day');
                foreach ($rasp_day as $day) {
                    $message .= $day->text() . "\n" . str_repeat('.', 25);
                }
                $rasp_para = $item->find('.rasp-para');
                foreach ($rasp_para as $para) {
                    $message .= $para->text();
                }
            }
            if ($date === '') {
                return nl2br($message);
            }
            try {
                $at = new DateTime($date);
                $day = $at->format('d');
                return "Расписание на " . $day;
            } catch (Exception) {
                return 'Неправильный формат даты';
            }
        } catch (InvalidSelectorException) {
            return 'Чёта не получилось';
        }
    }
}
