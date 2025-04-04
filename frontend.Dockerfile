FROM node:22.14

WORKDIR /app

COPY package.json ./

RUN npm install

COPY . .

RUN npm run build

EXPOSE 8080

CMD ["npm", "run", "dev", "--", "--host=0.0.0.0"]
