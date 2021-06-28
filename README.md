
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


1. Clone this reposotory.
2. Copy `.env.example` as `.env` (change db configs if needed, but everything should be working by default)
3. Build project `docker-compose build` from `docker` folder where `docker-compose.yaml` is located
4. Run Docker containers `docker-compose up -d`
5. install all dependencies (Composer, NPM) `docker exec php composer install`, `docker exec php npm install`
6. Build Css and JS files for public `docker exec php npm run dev`
7. Migrate database with seeds `docker exec php php artisan migrate --seed`
8. Launch websockets server `docker exec php php artisan websockets:serve` (do it from new terminal window)
9. open http://127.0.0.1:8001

## User data information
Seeded test user passwords will be "password"

## Container access information
app will be accessable to localhost port 8001 "http://127.0.0.1:8001/"

## Data base access information 
Database passwords could be seend in `docker/docker-compose.yaml` file

---
If there is trouble building docker file? Try `docker-compose build --force-rm --no-cache`
