FROM nginx:1.22.1-alpine

# Set working directory
WORKDIR /var/www

# Copy assets
COPY . /var/www/public

# Expose port. this is not required if we manage from docker-compose
EXPOSE 80

# Start http server
CMD ["nginx", "-g", "daemon off;"]
