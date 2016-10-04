<?php

namespace App\Http\Services\Billings;

use App\Http\Interfaces\Billings\IBillings;

class BillingsService implements IBillings {

    protected $connection;

    public function __construct() {
        $config = new \Doctrine\DBAL\Configuration();
        $connectionParams = array(
            'dbname' => 'pbx',
            'user' => 'biling',
            'password' => 'RX)4j9MUtw{xUUaY',
            'host' => '10.0.0.78',
            'driver' => 'pdo_pgsql',
        );
        $this->connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    }

    public function getAllBillings() {
        $pgQuerySql = 'SELECT * FROM bo_get_stat_callcenter(1) limit 10';
        $stmt = $this->connection->query($pgQuerySql);
        while ($row = $stmt->fetch()) {
            echo $row['headline'];
        }
        die;
    }

}
