<?php

declare(strict_types=1);

require_once('../vendor/autoload.php');

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;

class SiteParser
{

    public function getSchedule(string $group_name, string $date = ''): string
    {
        /*
         * TODO:
         *  - Преобразовать полученный массив в строку с расписанием
         *  - Сделать формирование расписания по 3 сценариям: сегодня, завтра, неделя
         *  - Сделать перевод для вводимой даты, по типу: сегодня => today, завтра => tomorrow
         */

//        $schedule = $this->makeSchedule($group_name);

//        if ($date === '') {
//            //неделя
//            return 'week';
//        }
//
//        try {
//            //сегодня, завтра
//            $at = new DateTime($date);
//            $day = $at->format('d');
//            return 'day';
//        } catch (Exception $e) {
//            return 'Неправильный формат даты';
//        }

        return '';
    }

    private function makeSchedule(string $group_name): array
    {
        $document = new Document(
            'https://uspu.ru/education/eios/schedule/?group_name=' . $group_name,
            true
        );

        $content = [];
        try {
            $rasp_item = $document->find('.rasp-item');
            foreach ($rasp_item as $i => $item) {

                $rasp_day = $item->find('.rasp-day');
                foreach ($rasp_day as $day) {
                    $content['rasp-day'][$i] = $day->text();
                }
                $rasp_week = $item->find('.rasp-week');
                foreach ($rasp_week as $week) {
                    $content['rasp-week'][$i] = $week->text();
                }
                $rasp_para = $item->find('.rasp-para');
                foreach ($rasp_para as $para) {
                    $para_time = $para->find('.para-time');
                    foreach ($para_time as $time) {
                        $content['para-time'][$i][] = $time->text();
                    }
                    $rasp_desc = $para->find('.rasp-desc');
                    foreach ($rasp_desc as $desc) {
                        $content['rasp-desc'][$i][] = trim($desc->text());
                    }
                }
            }

            return $content;
        } catch (InvalidSelectorException $e) {
            return ['ERROR Code' => $e->getCode(), 'ERROR Message' => $e->getMessage()];
        }
    }
}
