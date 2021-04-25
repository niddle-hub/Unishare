<?php

use DigitalStar\vk_api\vk_api;
use DigitalStar\vk_api\VkApiException;

require 'SiteParser.php';
require 'User.php';
require 'config.php';

$vk = vk_api::create(TOKEN, VERSION)->setConfirm(CONFIRM);
$data = $vk->initVars($id, $message, $payload, $user_id, $type);

$User = new User($id);
$SiteParser = new SiteParser();

$btnBack = $vk->buttonText(smile\globe . ' На главную', 'red', ['command' => 'back']);
$btnNews = $vk->buttonText(smile\newspaper . ' Новости', 'white', ['command' => 'news']);
$btnANotice = $vk->buttonText(smile\bell . ' Подписаться на новостную рассылку', 'green', ['command' => 'a_notice']);
$btnDNotice = $vk->buttonText(smile\crossedbell . ' Отписаться от новостной рассылки', 'red', ['command' => 'd_notice']);
$btnTable = $vk->buttonText(smile\tablet . ' Расписание', 'blue', ['command' => 'table']);
$btnGroup = $vk->buttonText(smile\peoples . ' Группа', 'green', ['command' => 'group']);
$btnToday = $vk->buttonText(smile\whitecircle . ' Сегодня', 'green', ['command' => 'today']);
$btnTomorrow = $vk->buttonText(smile\blackcircle . ' Завтра', 'blue', ['command' => 'tomorrow']);
$btnDate = $vk->buttonText(smile\calendar . ' Дата', 'white', ['command' => 'date']);
$btnWeek = $vk->buttonText(smile\books . ' Неделя', 'white', ['command' => 'week']);
$btnCancel = $vk->buttonText(smile\leftarrow . ' Назад', 'red', ['command' => 'cancel']);

if ($type === 'message_new') {
    (isset($payload)) ? $payload = $payload['command'] : $payload = null;
    if ($payload === 'start' || $message === 'Начать') {
        if ($User->addNew($id)) {
            $vk->sendButton(
                $id,
                smile\hellohand . " Привет, %a_full% ! Я Расписание-Бот.\n" .
                smile\fire . " Тут ты можешь узнать расписание для своей группы, а также свежие новости со всего универа. 
                Обязательно подпишись на новостную рассылку в разделе новости !\n" .
                smile\fingerdown . " А теперь тыкай на кнопки и смотри что я могу!\n\n".
                smile\shh . "(не забудь сменить группу в настройках)", [[$btnTable, $btnNews, $btnGroup]]
            );
        } else {
            $vk->sendButton($id, smile\globe . ' Что интересует ?', [[$btnTable, $btnNews, $btnGroup]]);
        }
    }

    if ($payload === 'back') {
        $loadedUser = $User->load();
        $loadedUser->waitgroup = false;
        $User->update($loadedUser);
        $vk->sendButton($id, smile\globe . ' Что интересует ?', [[$btnTable, $btnNews, $btnGroup]]);
    }

    if ($payload === 'cancel') {
        $loadedUser = $User->load();
        $loadedUser->waitdate = false;
        $User->update($loadedUser);
        $vk->sendButton($id, 'Меню', [[$btnToday, $btnTomorrow, $btnWeek, $btnDate], [$btnBack]]);
    }

    if ($payload === 'table') {
        $vk->sendButton($id, smile\orangediamond . $User->getData()['group'], [[$btnToday, $btnTomorrow, $btnWeek, $btnDate], [$btnBack]]);
    }

    if ($payload === 'news') {
        if ($User->getData()['notification']) {
            $vk->sendButton($id, 'Новости на ' . smile\numberone . ' канале', [[$btnDNotice], [$btnBack]]);
        } else {
            $vk->sendButton($id, 'Новости на ' . smile\numberone . ' канале', [[$btnANotice], [$btnBack]]);
        }
    }

    if ($payload === 'a_notice') {
        $loadedUser = $User->load();
        $loadedUser->notification = true;
        $User->update($loadedUser);
        $vk->sendButton($id, smile\greenmark . ' Вы успешно подписались на рассылку!', [[$btnDNotice], [$btnBack]]);
    }

    if ($payload === 'd_notice') {
        $loadedUser = $User->load();
        $loadedUser->notification = false;
        $User->update($loadedUser);
        $vk->sendButton($id, smile\exclamation . ' Вы больше не будете получать новости. И вкусяншки тоже!', [[$btnANotice], [$btnBack]]);
    }

    if ($payload === 'group') {
        $loadedUser = $User->load();
        $loadedUser->waitgroup = true;
        $User->update($loadedUser);
        $vk->sendButton(
            $id,
            smile\pencil . " Напиши свою группу!\n" .
            smile\tablet . " Доступны все группы:\n" .
            "Формат записи: исит-0000 или ми-1111 или как там ваша гурппа назвается...", [[$btnBack]]
        );
    }

    if ($payload === 'today' || mb_strtolower($message) === 'сегодня') {
/*        $schedule = $SiteParser->getSchedule($User->getData()['group'], today);
        $vk->reply($schedule);*/
        $vk->reply('скоро');
    }

    if ($payload === 'tomorrow' || mb_strtolower($message) === 'завтра') {
/*        $schedule = $SiteParser->getSchedule($User->getData()['group'], tomorrow);
        $vk->reply($schedule);*/
        $vk->reply('скоро');
    }

    if ($payload === 'week' || mb_strtolower($message) === 'неделя'){
        $schedule = $SiteParser->getSchedule($User->getData()['group']);
        try {
            $vk->reply($schedule);
        } catch (VkApiException $e) {
            $vk->reply("too long message: " . mb_strlen($message));
        }
    }

    if ($payload === 'date') {
/*        $loadedUser = $User->load();
        $loadedUser->waitdate = true;
        $User->update($loadedUser);
        $vk->sendButton($id, "Введи дату " . smile\calendar, [[$btnCancel]]);*/
        $vk->reply('скоро');
    }

    if ($payload !== 'cancel' && $User->getData()['waitdate']) {
        $schedule = $SiteParser->getSchedule($User->getData()['group'], $message);
        $vk->reply($schedule);
    }

    if ($payload !== 'back' && $User->getData()['waitgroup']) {
        if (is_string($message)) {
            $loadedUser = $User->load();
            $loadedUser->group = $message;
            $loadedUser->waitgroup = false;
            $User->update($loadedUser);
            $vk->sendButton($id, smile\greenmark . ' Ваша группа: ' . $message, [[$btnTable, $btnNews, $btnGroup]]);
        } else {
            $vk->reply("Нужна строка");
        }
    }
}

if ($type === 'group_join') {
    $event_id = $data->object->user_id;
    $vk->sendMessage($event_id, smile\fire . ' +100 к удаче на экзамене за подписку!');
}

if ($type === 'group_leave') {
    $event_id = $data->object->user_id;
    $vk->sendMessage($event_id, 'Уже уходите ? ' . smile\worry . ' Будем ждать вас снова!');
}