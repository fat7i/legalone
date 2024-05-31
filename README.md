# Logger Aggregation

# Project Setup Guide

### 1. Clone the Project
Clone the project repository to your local machine.

### 2. Build Docker Image
Navigate to the project folder and execute the following command to build the Docker image locally:

    docker build -t legalone/php-fpm --build-arg USER_ID=$(id -u) --build-arg GROUP_ID=$(id -g) .

This command will build the Docker image and ensure that the permissions of your local user are copied to a Docker user named `dev`.

### 3. Start Containers
Start all the containers using Docker Compose:

    docker compose up -d

### 4. Access the Application:
You can access the application through your web browser at:
http://localhost:8080/count
---
### Sample Query Parameters
You can use the following sample query parameters for testing:

http://localhost:8080/count?statusCode=201&startDate=15-08-2018&endDate=18-08-2018&serviceNames[0]=INVOICE-SERVICE&serviceNames[1]=USER-SERVICE

### Logs file path
The logs are located at:
`logstash/logs.log`

---
### Running Tests
To run tests, follow these steps:

1. Access your PHP Docker container:
   ````
      docker exec -it php bash
   ````

2. Run PHPUnit tests:
   ````
   php vendor/bin/phpunit
    ````

Have a Query? Feel free to reach me out on [iam.mohamed.f.ali@gmail.com](mailto:iam.mohamed.f.ali@gmail.com)