# FROM node:17.1.0
# WORKDIR /server
# COPY package.json .
# RUN npm install
# ENTRYPOINT ["node", "./src/index.js"]

FROM node:17.1.0
WORKDIR /server
COPY package.json .

RUN npm i -g nodemon
RUN npm install
ENTRYPOINT ["npm", "run", "dev"]

# docker build . -t node_server
# docker run -p 3000:3000 -v $PWD/src:/server/src node_server
