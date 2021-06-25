
## My Chat description

"My Chat" is Laravel based application which allow users to send messages using websockets

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

## Main composer package used

- yajra/laravel-datatables-oracle - used to process datatables on server side.
- spatie/laravel-medialibrary - used to process user files (Users avatar images). This package allow also regenerate image conversion with different sizes (useful to generate server image for optimization)
- beyondcode/laravel-websockets - used to process websockets server side (send/receive)


## Main NPM package used
- axios - used to process ajax request
- laravel-echo - used to process websockets information on client side
- cropperjs - used to give option to force user to upload image and crop image by aspect ration (1:1), to create square size image
- datatables.net - used to process datatables on client side  

## Project "My Chat" setup guide
1. Set up Docker server
1.1 Build docker container running build on docker compose file (docker/docker-compose.yaml) 
1.2 Launch docker server by running "docker-compose up" on docker compose file (docker/docker-compose.yaml) 
2. set up dependencies
2.1 Access docker server terminal "docker exec -it php sh", "php" is container name where php application is installed
2.2 "Setup composer dependencies" - form terminal go to project root directory and initialize composer dependencies "composer install"
2.3 "Setup npm dependencies" - form terminal  go to project root directory and initialize npm dependencies "npm install"
2.4 "Setup Javascript(JS) and CSS" - Run laravel mix to generate JS and CSS files "npm run dev"
3. Setup database structure
3.1 Run laravel migrations with seeds "php artisan migrate --seed" this will automatically add dummy data for important project tables
4. Launch websockets server - In docker php container you must run command "php artisan websockets:serve" this will launch websockets server, good advice to launch server form other terminal windows, because it will lock terminal form websockets is launch.Websockets will use there or server to process data sending and receiving.

## Useful
1. app will be accessable to localhost port 8001 "http://127.0.0.1:8001/"
2. Seeded test user passwords all will be "password"
