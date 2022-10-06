FROM node:17.1.0

RUN npm i -g nodemon

WORKDIR /server
COPY package.json package.json
RUN npm install

ENTRYPOINT ["npm", "run", "dev"]