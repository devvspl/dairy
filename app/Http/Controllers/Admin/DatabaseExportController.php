<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DatabaseExportController extends Controller
{
    /**
     * Export the entire database as an SQL file download.
     */
    public function export(): StreamedResponse
    {
        $database = config('database.connections.mysql.database');
        $filename = $database . '_export_' . date('Y-m-d_H-i-s') . '.sql';

        $tables = DB::select('SHOW TABLES');
        $tableKey = 'Tables_in_' . $database;

        $response = new StreamedResponse(function () use ($tables, $tableKey, $database) {
            $handle = fopen('php://output', 'w');

            // Header comments
            fwrite($handle, "-- Database Export: {$database}\n");
            fwrite($handle, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
            fwrite($handle, "-- --------------------------------------------------------\n\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
            fwrite($handle, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
            fwrite($handle, "SET AUTOCOMMIT = 0;\n");
            fwrite($handle, "START TRANSACTION;\n\n");

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;

                // Drop table statement
                fwrite($handle, "-- --------------------------------------------------------\n");
                fwrite($handle, "-- Table structure for `{$tableName}`\n");
                fwrite($handle, "-- --------------------------------------------------------\n\n");
                fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");

                // Create table statement
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                if (!empty($createTable)) {
                    $createSql = $createTable[0]->{'Create Table'} ?? '';
                    fwrite($handle, $createSql . ";\n\n");
                }

                // Table data
                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    fwrite($handle, "-- Dumping data for `{$tableName}`\n\n");

                    foreach ($rows as $row) {
                        $values = [];
                        foreach ((array) $row as $value) {
                            if (is_null($value)) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = "'" . addslashes((string) $value) . "'";
                            }
                        }
                        $columns = array_map(function ($col) {
                            return "`{$col}`";
                        }, array_keys((array) $row));

                        fwrite($handle, "INSERT INTO `{$tableName}` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n");
                    }
                    fwrite($handle, "\n");
                }
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fwrite($handle, "COMMIT;\n");
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'application/sql');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
