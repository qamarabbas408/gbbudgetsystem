php artisan migrate:fresh --seed
php artisan db:seed --class=MappingRulesSeeder
 php artisan make:seeder MappingRulesSeeder  
 php artisan make:migration add_type_to_department_mappings 
 php artisan db:seed  
  php artisan make:controller DepartmentMappingController
  php artisan make:model DepartmentMapping -m
  php artisan view:clear 
  php artisan cache:clear
   php artisan config:clear