# https://hub.docker.com/_/nginx
FROM nginx:1.19.5

RUN apt-get update && apt-get install -y \
    nano

RUN rm /etc/nginx/conf.d/default.conf /var/log/nginx/*.log

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
