# API for Recipe Web Application Using Laravel Framework (Back End)

This Recipe application is designed with a stateless architecture and provides a RESTful API secured with token-based authentication. The API is consumed by a  [front-end application](https://github.com/mail2asik/web-recipe) built using React libraries.

## Technical Implementation
- **Docker**: Utilized Docker and Docker Compose for containerizing the application.
- **Nginx**: Used nginx as the http server
- **REST API**: Implemented token-based authentication and stateless architecture.
- **Repositories**: Applied a clean and reusable code approach.
- **AWS S3**: Stored recipe images in AWS S3.
- **Mailgun**: Used Mailgun for sending emails.
- **Cache**: Employed Memcache for caching user data to enhance performance.
- **Redis Queue**: Utilized Redis Queue to send emails via a queue system, improving performance.
- **Supervisor**: Single place to start, stop, and monitor PHP-FPM and Queue worker process

## Functionalities

This application includes essential features for managing user-generated recipes:
- Public users can view recipes posted by registered users.
- Any user can register for an account.
- Users must activate their accounts by verifying their email addresses.
- Users can log in to the application.
- Users can reset their passwords if forgotten.
- Users can post recipes with details such as Category, Title, Image, Ingredients, Short Description, and Long Description.
- User can edit and delete recipes
- Admins will review and approve recipes.
- Users will receive an email once their recipe is approved by an Admin.
- The recipe status will change from "PENDING" to "APPROVED".
- Approved recipes will be available to the public.
- Admins can reject recipes if they are invalid or spam.
- The Admin interface is developed using Laravel.
- Admins can log in and manage users and recipes.

**Note** : Admin interface is part of this project and is not a single-page application. It does not consume API endpoints.

## The following are the API Endpoints

**Ping**

GET|HEAD  api.recipe.local/api/ping 

**Authentication**

POST      api.recipe.local/api/auth/register 

POST      api.recipe.local/api/auth/login 

POST      api.recipe.local/api/auth/activate-by-url/{email}/{token} 

POST      api.recipe.local/api/auth/logout 

POST      api.recipe.local/api/auth/password-change

POST      api.recipe.local/api/auth/password-reminder 

POST      api.recipe.local/api/auth/password-reset

**User**

GET|HEAD  api.recipe.local/api/user

**Recipes**

POST      api.recipe.local/api/recipe 

GET|HEAD  api.recipe.local/api/recipe 

GET|HEAD  api.recipe.local/api/recipe/all 

GET|HEAD  api.recipe.local/api/recipe/recent 

GET|HEAD  api.recipe.local/api/recipe/view/{recipe_uid} 

GET|HEAD  api.recipe.local/api/recipe/{recipe_uid}

PUT       api.recipe.local/api/recipe/{recipe_uid} 

DELETE    api.recipe.local/api/recipe/{recipe_uid}