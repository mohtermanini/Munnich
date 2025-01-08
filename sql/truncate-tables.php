<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Config\Database;
use PDO;
use Exception;

/**
 * TruncateTables class for clearing all data from database tables.
 *
 * This class provides functionality to truncate all tables in a database,
 * ensuring the data is removed efficiently while managing foreign key constraints.
 */
class TruncateTables
{
    private PDO $conn;

    /**
     * Constructor to initialize the database connection.
     *
     * @throws Exception If the database connection fails.
     */
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Clear all data from all tables in the database.
     *
     * This method disables foreign key checks to handle table relationships
     * and uses `TRUNCATE TABLE` for efficient deletion.
     *
     * @return void
     */
    public function clearAllTables(): void
    {
        try {
            $this->conn->exec('SET FOREIGN_KEY_CHECKS = 0;');

            $tables = $this->getAllTableNames();

            foreach ($tables as $table) {
                $query = "TRUNCATE TABLE {$table}";
                $this->conn->exec($query);
                echo "Truncated table: {$table}\n";
            }

            $this->conn->exec('SET FOREIGN_KEY_CHECKS = 1;');

            echo "All tables truncated successfully.\n";
        } catch (Exception $e) {
            echo "Failed to truncate tables: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Retrieve all table names from the database.
     *
     * @return array List of table names.
     */
    private function getAllTableNames(): array
    {
        $query = "SHOW TABLES";
        $stmt = $this->conn->query($query);

        $tables = [];
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }

        return $tables;
    }
}

// Run the truncate script
$truncate = new TruncateTables();
$truncate->clearAllTables();
