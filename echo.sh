#!/bin/bash

PORT=8000

echo "Bash Echo Server is running and listening on port $PORT..."

while true; do
  (echo -ne "HTTP/1.1 200 OK\r\n"; cat) | nc -l -p $PORT
done