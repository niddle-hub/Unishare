<?php

require_once '../lib/simple_html_dom.php';

class SiteParser extends simple_html_dom
{
    public function getSchedule(string $group_name, string $date = ''): string
    {
        $this->load_file('https://uspu.ru/education/eios/schedule/?group_name=' . $group_name);
        if ($date == '') {
            return 'week';
        } else {
            try {
                $at = new DateTime($date);
                return $at->format('d.m.Y');
            } catch (Exception) {
                return 'Неправильный формат даты';
            }
        }
    }

    //TODO: Сделать перевод для вводимой даты, по типу: сегодня => today, завтра => tomorrow
    //TODO: Сделать получение расписания по 3 сценариям: сегодня, завтра, неделя

//    private function makeSchedule()
//    {
//        $content = [];
//        $this->load_file('https://uspu.ru/education/eios/schedule/?group_name=' . $group_name);
//
//        $count = count($this->find('.rasp-item'));
//        for ($i = 0; $i <= $count; $i++) {
//            $content['week_day'][] = $this->find('.rasp-week', $i)->plaintext;
//            $content['rasp_day'][] = $this->find('.rasp-day', $i)->plaintext;
//            $content['para_time'][] = $this->find('.para-time', $i)->plaintext;
//        }
//        return $content;
//    }
}
