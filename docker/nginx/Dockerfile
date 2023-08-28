FROM nginx:alpine

COPY nginx.conf /etc/nginx/

VOLUME /var/www/dumpit

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
