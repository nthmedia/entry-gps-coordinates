<?php

namespace nthmedia\entrygpscoordinates\migrations;

use Craft;
use craft\db\Migration;
use craft\helpers\ElementHelper;
use nthmedia\entrygpscoordinates\fields\EntryCoordinates as EntryCoordinatesField;

/**
 * Alters content columns for Entry Coordinates fields from varchar(255) to text.
 *
 * In Craft 4, field values are stored in dedicated content table columns. This plugin
 * serializes coordinates + address data as a single JSON string. Since 2.4.0 that string
 * can exceed 255 characters, causing "Map Location should contain at most 255 characters".
 * This migration changes those columns to TEXT (65,535 chars) so existing installs
 * are fixed without manually re-saving each field.
 *
 * Craft 5 stores content in JSON blobs (no per-field columns), so this migration
 * is a no-op there.
 */
class m240317_000000_alter_entry_coordinates_columns_to_text extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        // Craft 5: content lives in JSON blobs; fieldColumnFromField() was removed.
        // Skip so we don't call non-existent APIs.
        if (!method_exists(ElementHelper::class, 'fieldColumnFromField')) {
            return true;
        }

        $fieldsService = Craft::$app->getFields();
        $db = Craft::$app->getDb();
        $schema = $db->getSchema();
        $rawTablePrefix = $db->tablePrefix ?? '';

        // Collect DB column names for every Entry Coordinates field (e.g. field_mapLocation_abc123).
        $columnsToAlter = [];
        foreach ($fieldsService->getAllFields() as $field) {
            if (!$field instanceof EntryCoordinatesField) {
                continue;
            }
            $columnsToAlter[] = ElementHelper::fieldColumnFromField($field);
        }

        if (empty($columnsToAlter)) {
            return true;
        }

        // Craft 4 can use one or more content tables (content, content_123, etc.).
        // Only alter columns in those tables.
        $tableNames = $schema->getTableNames();
        foreach ($tableNames as $tableName) {
            // Must start with configured table prefix (e.g. craft_).
            if ($rawTablePrefix !== '' && strpos($tableName, $rawTablePrefix) !== 0) {
                continue;
            }
            $shortName = $rawTablePrefix !== '' ? substr($tableName, strlen($rawTablePrefix)) : $tableName;
            // Keep only content and content_* tables.
            if ($shortName !== 'content' && strpos($shortName, 'content') !== 0) {
                continue;
            }

            $tableSchema = $schema->getTableSchema($tableName);
            if ($tableSchema === null) {
                continue;
            }

            foreach ($columnsToAlter as $columnName) {
                if (!isset($tableSchema->columns[$columnName])) {
                    continue;
                }
                $currentType = $tableSchema->columns[$columnName]->dbType;
                // Already TEXT/MEDIUMTEXT/LONGTEXT — avoid redundant ALTER.
                if (stripos($currentType, 'text') !== false) {
                    continue;
                }
                $this->alterColumn($tableName, $columnName, $this->text());
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        // Intentionally no-op: reverting to varchar(255) would truncate existing data.
        return true;
    }
}
