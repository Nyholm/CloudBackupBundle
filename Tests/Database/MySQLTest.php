<?php

namespace Dizda\CloudBackupBundle\Tests\Database;

use Dizda\CloudBackupBundle\Database\MySQL;

/**
 * Class MySQLTest.
 */
class MySQLTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test different commands.
     */
    public function testGetCommand()
    {
        // dump all databases
        $mysql = new MySQLDummy(array(
            'mysql' => array(
                'all_databases' => true,
                'db_host'     => 'localhost',
                'db_port'     => 3306,
                'database'    => 'dizbdd',
                'db_user'     => 'root',
                'db_password' => 'test',
            ),
        ), '/var/backup/');
        $this->assertEquals($mysql->getCommand(), "mysqldump --host='localhost' --port='3306' --user='root' --password='test' --all-databases > /var/backup/mysql/all-databases.sql");

        // dump specified database
        $mysql = new MySQLDummy(array(
            'mysql' => array(
                'all_databases' => false,
                'db_host'     => 'localhost',
                'db_port'     => 3306,
                'database'    => 'dizbdd',
                'db_user'     => 'root',
                'db_password' => 'test',
            ),
        ), '/var/backup/');
        $this->assertEquals($mysql->getCommand(), "mysqldump --host='localhost' --port='3306' --user='root' --password='test' dizbdd > /var/backup/mysql/dizbdd.sql");

        // dump specified database
        $mysql = new MySQLDummy(array(
            'mysql' => array(
                'all_databases' => false,
                'db_host'     => 'somehost',
                'db_port'     => 2222,
                'database'    => 'somebdd',
                'db_user'     => 'mysql',
                'db_password' => 'somepwd',
            ),
        ), '/var/backup/');
        $this->assertEquals($mysql->getCommand(), "mysqldump --host='somehost' --port='2222' --user='mysql' --password='somepwd' somebdd > /var/backup/mysql/somebdd.sql");

        // dump specified database with no auth
        $mysql = new MySQLDummy(array(
            'mysql' => array(
                'all_databases' => false,
                'db_host'     => 'somehost',
                'db_port'     => 2222,
                'database'    => 'somebdd',
                'db_user'     => null,
                'db_password' => null,
            ),
        ), '/var/backup/');
        $this->assertEquals($mysql->getCommand(), 'mysqldump  somebdd > /var/backup/mysql/somebdd.sql');

        // dump all databases with no auth
        $mysql = new MySQLDummy(array(
            'mysql' => array(
                'all_databases' => true,
                'db_host'     => 'somehost',
                'db_port'     => 2222,
                'database'    => 'somebdd',
                'db_user'     => null,
                'db_password' => null,
            ),
        ), '/var/backup/');
        $this->assertEquals($mysql->getCommand(), 'mysqldump  --all-databases > /var/backup/mysql/all-databases.sql');
    }
}

class MySQLDummy extends MySQL
{
    public function getCommand()
    {
        return parent::getCommand();
    }
}
