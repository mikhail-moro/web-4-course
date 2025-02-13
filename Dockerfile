FROM ubuntu:latest
WORKDIR /app
COPY . .

RUN apt update
CMD ["echo.sh"]