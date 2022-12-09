## VK-Bot
Универсальный бот ВКонтакте. Подойдёт для Minecraft серверов.

Это говно давно устарело, не юзайте

## Особенности
- Использование LongPoll
- Поддержка плагинов
- Поддержка следующих событий: лайк, дизлайк, новый комментарий, удаление комментария, подписка на группу, отписка от группы, новое сообщение в группу, новый пост в группе

## Функции
- Отправка сообщений пользователю
- Отправка сообщений в чат
- Отправка сообщений с приложениями (фотография, документ и т.п)

## Установка для Linux (Debian)
Минимальная версия PHP - 8.0
- sudo apt install apt-transport-https lsb-release ca-certificates wget -y
- sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg 
- sudo sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'
- sudo apt update
- sudo apt install php-8.0
- sudo apt install php-xml
- sudo apt install php-mbstring
- sudo apt install php-curl
- sudo apt install php-yaml
- В группе нужно включить LongPoll версии 5.95, и выдать все права в типах событий
- Получить токен и сохранить его
- В settings.yml отредактировать айди группы и токен
- cd VK-Bot
- Выдаём права на исполняемый файл (chmod 777 ./start.sh)
- ./start.sh (желательно запускать в скрине)

## Обратная связь
Если вы столкнулись с проблемами при установке, напишите мне в:
ВКонтакте - vk.com/sjezu, Telegram - @patt228

## Поддержать
Qiwi - https://qiwi.com/n/STRANGEX
