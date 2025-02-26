# Healthcare-Management

#Create a Controller :
php bin/console make:controller ControllerName

#Create an Entity (Model) :
php bin/console make:entity EntityName

#migration :
php bin/console make:migration
php bin/console doctrine:migrations:migrate

#Clear Cache :
php artisan cache:clear

#Run Symfony Development Server :
php bin/console server:run
