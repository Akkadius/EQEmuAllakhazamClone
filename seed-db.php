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
exec('mysql -h mariadb -uroot -proot -e "CREATE DATABASE peq"');
echo "Sourcing data... Ignore password warnings below...\n";
chdir($temporary_location);
exec("mysql -h mariadb -uroot -proot peq < peqbeta.sql");
exec("mysql -h mariadb -uroot -proot peq < player_tables.sql");
chdir($origin_directory);
echo "Seeding complete!\n";