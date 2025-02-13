FROM ubuntu:latest
WORKDIR /app
COPY . .

RUN apt-get update && apt-get install netcat-traditional
RUN chmod +x ./echo.sh

CMD ["./echo.sh"]