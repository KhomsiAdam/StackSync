<?php

namespace App\Core;

use PDO;
use Dotenv\Dotenv;
use App\Config\Dbh;
use App\Config\Scripts;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable('./');
$dotenv->load();

// This class creates files depending on user input and based on template files for migrations, seeders, controllers and models
class Make
{


    /** File creation
     * @param string $template the template filename and extention .txt from 'app/core/templates"
     * @param string $filename the created filename with .php extention
     * @param string $type type of file created: migration, seeder, controller (model is created with controller)
     */
    public static function createFile($template, $filename, $type)
    {
        // Get template
        $template_content = file_get_contents(__DIR__ . '/templates/' . $template);
        // Create the file under the specified type folder
        $path = dirname(__DIR__) . '/' . $type . '/' . $filename;
        $file = fopen($path, 'w');
        // Copy the content of the template into the created file
        if (fwrite($file, $template_content) === FALSE) {
            Scripts::log("Cannot write to file ($filename).");
            exit;
        }
        // Return the created file
        return file_get_contents($path);
    }

    /** Get the current tables in the database
     * @return array of database tables
     */
    public static function currentTables()
    {
        $sql = "SHOW TABLES";
        $db = new Dbh();
        $db->connect();
        $stmt = $db->conn->prepare($sql);
        $stmt->execute();
        $db_tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $db_tables;
    }

    // Make migration (composer make:migration)
    public static function migration()
    {
        // Get migration number (between 1 & 9999)
        do {
            Scripts::echo("Enter a number from 1 to 9999 (this decides the order in which your migrations will be applied and sorted):");
            $number = fgets(STDIN);
        } while (trim($number) < 1 || trim($number) > 9999);

        // Get current database tables
        $db_tables = self::currentTables();
        // Remove migrations table
        if (($key = array_search('migrations', $db_tables)) !== false) {
            unset($db_tables[$key]);
        }
        // Implode the tables array into one string with separated commas
        $all_tables = implode(', ', $db_tables);

        // Get migration type (create or update table)
        do {
            Scripts::echo("Enter migration type (create or update): ");
            $type = fgets(STDIN);
        } while (trim($type) !== 'create' && trim($type)  !== 'update');

        // If creating a table, ask for foreign key constraints
        if (trim($type)  === 'create') {

            // Get table name if it's not equal to an existing table name
            do {
                Scripts::echo("Enter your table name (must be different name from current tables in database: " . $all_tables . "):");
                $table = fgets(STDIN);
            } while (in_array(trim($table), $db_tables));

            do {
                Scripts::echo("Auto-increment the id? [ yes, no ] ");
                $increment = fgets(STDIN);
            } while (trim($increment)  !== 'yes' && trim($increment)  !== 'no');
            if (trim($increment) === 'yes') {
                $id_type = 'INT PRIMARY KEY AUTO_INCREMENT ';
            } else if (trim($increment) === 'no') {
                $id_type = 'VARCHAR(16) PRIMARY KEY ';
            }

            do {
                Scripts::echo("Add a foreign key reference? [ yes, no ] ");
                $fkey_ref = fgets(STDIN);
            } while (trim($fkey_ref)  !== 'yes' && trim($fkey_ref)  !== 'no');
            // Specify the referenced table
            if (trim($fkey_ref) === 'yes') {

                // Set the reference table if it exists
                do {
                    Scripts::echo("Specify your referenced table (" . $all_tables . "):");
                    $ref_table = fgets(STDIN);
                } while (!in_array(trim($ref_table), $db_tables));

                // Ask for referenced table id type
                do {
                    Scripts::echo("Is your referenced table's id type auto-incremented? [ yes, no ]");
                    $ref_type = fgets(STDIN);
                } while (trim($ref_type)  !== 'yes' && trim($ref_type)  !== 'no');

                // Ask for "ON DELETE CASCADE" constraint
                do {
                    Scripts::echo("Use 'ON DELETE CASCADE' constraint? [ yes, no ]");
                    $constraint = fgets(STDIN);
                } while (trim($constraint)  !== 'yes' && trim($constraint)  !== 'no');
            }
            // When updating a table, ask for the column to add
        } else if (trim($type)  === 'update') {

            // Set the table to update if it exists
            do {
                Scripts::echo("Specify the table you want to update (" . $all_tables . "):");
                $table = fgets(STDIN);
            } while (!in_array(trim($table), $db_tables));

            // Set the column name to add to the table
            Scripts::echo("Enter the name of the column you want to add: ");
            $column = fgets(STDIN);
        }

        // Declaring our arrays for placeholders to be replaced depending on user inputs
        $placeholders = [];
        $inputs = [];

        // Generate a migration id with a prefix of 'm', 4 digits length with leading zeroes
        $migration_id = str_pad(trim($number), 4, "0", STR_PAD_LEFT);

        // Generate class name based on the generated id + the migration type + table name
        // (ex: migration number = 1, migration type = 'create', table name = 'user' => class name = m0001_create_user_table)
        $classname = 'm' . $migration_id . '_' . trim($type) . '_' . trim($table) . '_table';

        // Generate filename from classname by adding '.php' extension
        $filename = $classname . ".php";

        // Depending on migration type...
        switch (trim($type)) {
            case 'create':

                // Create the migration file based on a template and Get the created file
                $created_file = self::createFile("migration__template--create.txt", $filename, 'migrations');

                // Add foreign key constraint when it is approved
                if (trim($fkey_ref) === 'yes') {
                    if (trim($ref_type) === 'yes') {
                        $ref_type = 'INT NOT NULL';
                    } else if (trim($ref_type) === 'no') {
                        $ref_type = 'VARCHAR(16) NOT NULL';
                    }        
                    $comma = ',' . "\n\t\t\t\t";
                    $parenteses = '';
                    $fkey = 'FOREIGN KEY';
                    $id = '(' . trim($ref_table) . '_id)';
                    $ref_id = trim($ref_table) . '_id';
                    $ref = 'REFERENCES ' . trim($ref_table) . '(' . $ref_id . ')';
                    // Add "ON DELETE CASCADE" constraint when it is approved
                    if (trim($constraint) === 'yes') {
                        $cascade = 'ON DELETE CASCADE)';
                    }
                } else {
                    $ref_type = '';
                    $comma = '';
                    $parenteses = ')';
                    $fkey = '';
                    $id = '';
                    $ref_id = '';
                    $ref = '';
                    $cascade = '';
                }

                // Populate the arrays of placeholders and data depending on user inputs
                array_push($placeholders, '{{ class }}', '{{ table }}', '{{ id_type }}', '{{ foreign_key }}', '{{ id }}', '{{ ref_id }}', '{{ ref }}', '{{ cascade }}', '{{ ref_type }}', '{{ , }}', '{{ ) }}');
                array_push($inputs, $classname, trim($table), $id_type, $fkey, $id, $ref_id, $ref, $cascade, $ref_type, $comma, $parenteses);

                // Replace the placeholders by their data counterparts
                $replaced = str_replace($placeholders, $inputs, $created_file);

                break;
            case 'update':

                // Create the migration file based on a template and Get the created file
                $created_file = self::createFile("migration__template--update.txt", $filename, 'migrations');

                // Populate the arrays of placeholders and data depending on user inputs
                array_push($placeholders, '{{ class }}', '{{ table }}', '{{ column }}');
                array_push($inputs, $classname, trim($table), trim($column));

                // Replace the placeholders by their data counterparts
                $replaced = str_replace($placeholders, $inputs, $created_file);

                break;
        }

        // Place the new content in the file
        file_put_contents(dirname(__DIR__) . '/migrations/' . $filename, $replaced);
    }

    // Make seeder (composer make:seeder)
    public static function seeder()
    {

        // Get current database tables
        $db_tables = self::currentTables();
        // Remove migrations table
        if (($key = array_search('migrations', $db_tables)) !== false) {
            unset($db_tables[$key]);
        }

        // Get current seeded table names
        $seeders = scandir(dirname(dirname(__DIR__)) . '/app/seeders');
        $seeded_tables = [];
        foreach ($seeders as $key => $value) {
            $value = substr($value, 0, strpos($value, 'Seeder.php'));
            array_push($seeded_tables, lcfirst($value));
        }

        $unseeded_tables = array_diff($db_tables, $seeded_tables);
        // Implode the tables array into one string with separated commas
        $unseeded_tables_implode = implode(', ', array_filter($unseeded_tables));

        // Set the table to seed if it exists
        do {
            Scripts::echo("Specify the table you want to seed: (available tables to seed: " . $unseeded_tables_implode . "):");
            $table = fgets(STDIN);
        } while (!in_array(trim($table), $unseeded_tables));

        $table = trim($table);
        $tableUpper = ucfirst($table);

        // Ask for id incrementation
        do {
            Scripts::echo("Is your id auto incremented? [ yes, no ] ");
            $increment = fgets(STDIN);
        } while (trim($increment)  !== 'yes' && trim($increment)  !== 'no');

        // If no auto increment on id
        if (trim($increment)  === 'no') {
            // Ask for randomly generated id
            do {
                Scripts::echo("Generate random id? (with customizable length and optional prefix)? [ yes, no ] ");
                $generated = fgets(STDIN);
            } while (trim($generated)  !== 'yes' && trim($generated)  !== 'no');

            if (trim($generated) === 'yes') {
                $id = "Seeders::generateId('')";
            } else if (trim($generated) === 'no') {
                $id = "''";
            }
        } else if (trim($increment)  === 'yes') {
            $id = "''";
        }

        // Ask for importing column names as keys
        do {
            Scripts::echo("Import table columns as keys? [ yes, no ] ");
            $keys = fgets(STDIN);
        } while (trim($keys)  !== 'yes' && trim($keys)  !== 'no');

        if (trim($keys)  === 'yes') {
            // Connect to the database
            $db = new Dbh();
            $db_conn = $db->connect();

            // Get columns from table
            $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$_ENV[DB_NAME]' AND TABLE_NAME='$table'";

            $stmt = $db_conn->prepare($sql);
            $stmt->execute();
            $table_cols = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Remove date columns
            foreach ($table_cols as $key => $value) {
                if (strpos($value, '_updated_at') !== false || strpos($value, '_created_at') !== false) unset($table_cols[$key]);
            }

            $columns = '';
            foreach ($table_cols as $column) {
                if ($column !== $table . '_id') $columns .= PHP_EOL . "\t\t\t" . "'" . $column . "' => '',";
            }
        } else if (trim($keys)  === 'no') {
            $columns = '';
        }

        // Declaring our arrays for placeholders to be replaced depending on user inputs
        $placeholders = [];
        $inputs = [];

        // Generate class name based on the table name
        $classname = $tableUpper . 'Seeder';

        // Generate filename from classname by adding '.php' extension
        $filename = $classname . ".php";

        // Create the migration file based on a template and Get the created file
        $created_file = self::createFile("seeder__template--create.txt", $filename, 'seeders');

        // Populate the arrays of placeholders and data depending on user inputs
        array_push($placeholders, '{{ class }}', '{{ tableUpper }}', '{{ table }}', '{{ id }}', '{{ columns }}');
        array_push($inputs, $classname, $tableUpper, $table, $id, $columns);

        // Replace the placeholders by their data counterparts
        $replaced = str_replace($placeholders, $inputs, $created_file);

        // Place the new content in the file
        file_put_contents(dirname(__DIR__) . '/seeders/' . $filename, $replaced);
    }

    // Make controller & model (composer make:controller)
    public static function controller()
    {

        // Get current database tables
        $db_tables = self::currentTables();
        // Remove migrations table
        if (($key = array_search('migrations', $db_tables)) !== false) {
            unset($db_tables[$key]);
        }

        // Get current controlled table names
        $models = scandir(dirname(dirname(__DIR__)) . '/app/models');
        $controlled_tables = [];
        foreach ($models as $key => $value) {
            $value = substr($value, 0, strpos($value, 'Model.php'));
            array_push($controlled_tables, lcfirst($value));
        }

        $available_tables = array_diff($db_tables, $controlled_tables);

        // Implode the tables array into one string with separated commas
        $available_tables_implode = implode(', ', array_filter($available_tables));

        // Pick table to create controller/model for
        do {
            Scripts::echo("Specify the targeted table: (available tables: " . $available_tables_implode . "):");
            $table = fgets(STDIN);
        } while (!in_array(trim($table), $db_tables));

        $table = trim($table);
        $tableUpper = ucfirst($table);

        // Ask for id incrementation
        do {
            Scripts::echo("Is your id auto incremented? [ yes, no ] ");
            $increment = fgets(STDIN);
        } while (trim($increment)  !== 'yes' && trim($increment)  !== 'no');

        // If no auto increment on id
        if (trim($increment)  === 'no') {
            $id = 'Middleware::generateId()';
        } else if (trim($increment)  === 'yes') {
            $id = "''";
        }

        // Connect to the database
        $db = new Dbh();
        $db_conn = $db->connect();

        // Get columns from table
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$_ENV[DB_NAME]' AND TABLE_NAME='$table'";

        $stmt = $db_conn->prepare($sql);
        $stmt->execute();
        $table_cols = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $columns = implode(', ', $table_cols);

        $create_cols = [];
        $update_cols = [];

        // Remove id and date columns
        foreach ($table_cols as $key => $value) {
            if (strpos($value, '_updated_at') !== false || strpos($value, '_created_at') !== false || strpos($value, $table . '_id') !== false) {
                unset($table_cols[$key]);
            }
        }

        $c_validate_create_params = '';
        $c_validate_update_params = '';
        $c_setters = '';

        // Generate parameters validation and setters for controller
        foreach ($table_cols as $key => $value) {
            $c_setters .= PHP_EOL . "\t\t" . '$model->set' . ucwords($value) . '($' . $value . ')' . ";";
            $value = '$' . $value . ' = $this->validateParams("' . $value . '", $this->param["' . $value . '"], STRING)';
            array_push($create_cols, $value);
            array_push($update_cols, $value);
        }
        foreach ($create_cols as $param) {
            if ($param !== $table . '_id') $c_validate_create_params .= PHP_EOL . "\t\t" . $param . ";";
        }
        foreach ($update_cols as $param) {
            $c_validate_update_params .= PHP_EOL . "\t\t" . $param . ";";
        }

        $m_params = '';
        $m_setget = '';
        $table_cols_values = [];
        $bind_insert = '';

        // Generate properties, setters & getters for model, parameter binding for prepare statements
        foreach ($table_cols as $key => $value) {
            $m_params .= PHP_EOL . "\tprivate $" . $value . ";";
            $m_setget .= PHP_EOL . "\tfunction set" . ucwords($value) . '($' . $value . ') { $this->' . $value . ' = $' . $value . '; }' . PHP_EOL . "\tfunction get" . ucwords($value) . '() { return $this->' . $value . '; }';
            array_push($table_cols_values, ':' . $value);
            $bind_insert .= PHP_EOL . "\t\t$" . "stmt->bindParam(':" . $value . "', $" . "this->" . $value . ");";
        }

        if (count($table_cols) > 0) {
            $columns_insert = ', ' . implode(', ', $table_cols);
            $values_insert = ', ' . implode(', ', $table_cols_values);
        } else {
            $columns_insert = '';
            $values_insert = '';
        }

        // Declaring our arrays for placeholders to be replaced depending on user inputs
        $controller_placeholders = [];
        $controller_inputs = [];

        $model_placeholders = [];
        $model_inputs = [];

        // Generate class name based on the table name
        $controller_class = $tableUpper . 'ApiController';
        $model_class = $tableUpper . 'Model';

        // Generate filename from classname by adding '.php' extension
        $controller_name = $controller_class . ".php";
        $model_name = $model_class . ".php";

        // Create the migration file based on a template and Get the created file
        $controller_file = self::createFile("controller__template--create.txt", $controller_name, 'controllers');
        $model_file = self::createFile("model__template--create.txt", $model_name, 'models');

        // Populate the arrays of placeholders and data depending on user inputs
        array_push($controller_placeholders, '{{ controller }}', '{{ model }}', '{{ tableUpper }}', '{{ table }}', '{{ id }}', '{{ c_validate_create_params }}', '{{ c_validate_update_params }}', '{{ c_setters }}');
        array_push($controller_inputs, $controller_class, $model_class, $tableUpper, $table, $id, $c_validate_create_params, $c_validate_update_params, $c_setters);
        array_push($model_placeholders, '{{ model }}', '{{ tableUpper }}', '{{ table }}', '{{ m_params }}', '{{ m_setget }}', '{{ columns }}', '{{ columns_insert }}', '{{ values_insert }}', '{{ bind_insert }}');
        array_push($model_inputs, $model_class, $tableUpper, $table, $m_params, $m_setget, $columns, $columns_insert, $values_insert, $bind_insert);

        // Replace the placeholders by their data counterparts
        $controller_replaced = str_replace($controller_placeholders, $controller_inputs, $controller_file);
        $model_replaced = str_replace($model_placeholders, $model_inputs, $model_file);

        // Place the new content in the file
        file_put_contents(dirname(__DIR__) . '/controllers/' . $controller_name, $controller_replaced);
        file_put_contents(dirname(__DIR__) . '/models/' . $model_name, $model_replaced);
    }
}
