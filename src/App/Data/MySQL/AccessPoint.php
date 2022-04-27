<?php
namespace App\Data\MySQL;

use App\Data\AccessPointInterface;
use mysqli;

class AccessPoint implements AccessPointInterface
{
    private mysqli $dbConnection;


    public function __construct(array $params)
    {
        ['host' => $host, 'port' => $port, 'user' => $user, 'password' => $password, 'database' => $database] = $params;
        $mysqli = new mysqli($host, $user, $password, $database ?? '', $port ?? null);

        if($mysqli->errno) {
            throw new \Exception('db error');
        }
        $mysqli->set_charset('utf8mb4');

        $this->dbConnection = $mysqli;
    }

    public function execute(string $sql, array $params): mixed
    {
        $stmt = $this->dbConnection->prepare($sql);
        if(!$stmt) {
            throw new \Exception('Неверный запрос: '. $sql);
        }

        !empty($params) && $stmt->bind_param(str_repeat('s', count($params)), ...$params);

        if(!$stmt) {
            throw new \Exception('Неверный запрос: '. $sql);
        }

        if($stmt && $stmt->execute()) {
            $result = $stmt->get_result();
            if($result === false && !$stmt->errno) {
                return $stmt->insert_id ?? true;
            }

            $rows = [];
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $rows[] = $row;
            }

            return $rows;
        }
        return false;
    }
}