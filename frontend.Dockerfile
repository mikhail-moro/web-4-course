# Используем официальный образ Node.js версии 18
FROM node:18

# Устанавливаем рабочий каталог
WORKDIR /app

# Копируем package.json и package-lock.json
COPY package*.json ./

# Устанавливаем зависимости
RUN npm install

# Копируем исходный код
COPY . .

# Собираем проект
RUN npm run build

# Открываем порт 8080
EXPOSE 8080

# Запускаем сервер разработки
CMD ["npm", "run", "serve"]
