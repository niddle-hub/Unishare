<?php

namespace {
    date_default_timezone_set('Asia/Yekaterinburg');

    define("TOKEN", getenv('TOKEN'));
    define("CONFIRM", getenv('CONFIRM'));
    define("today", date('d'));
    define("tomorrow", date('d', strtotime('+1 day', strtotime(today))));

    const VERSION = '5.81';
}

namespace smile {
    const bell = '🔔';
    const blackcircle = '⚫';
    const books = '📚';
    const calendar = '📅';
    const crossedbell = '🔕';
    const exclamation = '❗';
    const fingerdown = '👇';
    const fire = '🔥';
    const globe = '🌐';
    const greenmark = '✅';
    const hellohand = '👋';
    const leftarrow = '⬅';
    const newspaper = '📰';
    const numberone = '1⃣';
    const orangediamond = '🔸';
    const pencil = '✏';
    const peoples = '👥';
    const redcross = '❌';
    const shh = '🤫';
    const tablet = '📋';
    const whitecircle = '⚪';
    const worry = '😰';
}
