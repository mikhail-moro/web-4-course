#!/bin/bash

PORT=8000

echo "Bash Echo Server is running and listening on port $PORT..."

while true; do
  # Ожидание входящего соединения и обработка одного запроса
  {
    # Чтение входящего HTTP-запроса
    read -r request

    # Игнорируем остальные заголовки запроса
    while read -r line; do
      if [[ "$line" == $'\r' ]]; then
        break
      fi
    done

    # Формируем HTTP-ответ с HTML-содержимым
    response="HTTP/1.1 200 OK\r\n"
    response+="Content-Type: text/html\r\n"
    response+="Connection: close\r\n"
    response+="\r\n"
    response+="<html><body><h1>Hello World!</h1></body></html>\r\n"

    # Отправляем ответ клиенту
    echo -e "$response"
  } | nc -l -p $PORT -q 1  # Обрабатываем одно соединение и завершаем его
done