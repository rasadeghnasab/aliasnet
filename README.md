# Clone the project

```
git clone git@github.com:rasadeghnasab/aliasnet.git
```

# How to Run?

## Using Docker: You need to have docker installed.

### Run application

1. Cd to the project directory

```
cd aliasnet
```

2. Create an image using the Dockerfile

```
docker build -t aliasnet-image .
```

4. Run the image

```
docker run -p 8000:8000 --name=aliasnet-container aliasnet-image
```

5. Call endpoints
6. Stop

```
docker stop aliasnet-container
```

7. Rerun the container:

```
docker start aliasnet-container
```

### Run tests

While the container is running:

```
docker exec aliasnet-container php artisan test
```

## Using PHP: You need to have PHP and Composer installed

### Run application

1. Cd to the project directory

```
cd aliasnet
```

2. Install dependencies

```
composer install
```

3. Serve the application

```
php artisan serve --port=8080
```

4. Call endpoints

5. Stop
```
press CTRL/CMD+c
```

### Run tests
```
php artisan test
```
