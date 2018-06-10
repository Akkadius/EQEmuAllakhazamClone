<?php
/**
 * Created by PhpStorm.
 * User: cmiles
 * Date: 6/9/18
 * Time: 9:46 PM
 */

$origin_directory = getcwd();
$temporary_location = "/tmp/db_source/";

/**
 * Download file
 */
$peq_dump = file_get_contents('http://edit.peqtgc.com/weekly/peq_beta.zip');
if (!file_exists($temporary_location)) {
    mkdir($temporary_location);
    file_put_contents($temporary_location . 'peq_beta.zip', $peq_dump);
}

/**
 * Source database
 */
echo "Installing unzip, mysql-client if not installed...\n";
exec("apt-get update && apt-get -y install unzip mysql-client");

echo "Unzipping peq_beta.zip...\n";
exec("unzip -o {$temporary_location}peq_beta.zip -d {$temporary_location}");

echo "Creating database PEQ...\n";
exec('mysql -h mariadb -uroot -proot -e "CREATE DATABASE peq"  2>&1 | grep -v \'Warning\'');

echo "Sourcing data...\n";
chdir($temporary_location);
exec("mysql -h mariadb -uroot -proot peq < peqbeta.sql  2>&1 | grep -v 'Warning'");
exec("mysql -h mariadb -uroot -proot peq < player_tables.sql  2>&1 | grep -v 'Warning'");
chdir($origin_directory);
echo "Seeding complete!\n";

/**
 * Unlink
 */
array_map('unlink', glob($temporary_location . "*.*"));
rmdir($temporary_location);