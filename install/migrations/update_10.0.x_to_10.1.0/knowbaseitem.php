<?php

/**
 * ---------------------------------------------------------------------
 *
 * GLPI - Gestionnaire Libre de Parc Informatique
 *
 * http://glpi-project.org
 *
 * @copyright 2015-2023 Teclib' and contributors.
 * @copyright 2003-2014 by the INDEPNET Development Team.
 * @licence   https://www.gnu.org/licenses/gpl-3.0.html
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * ---------------------------------------------------------------------
 */

/**
 * @var array $ADDTODISPLAYPREF
 * @var \DBmysql $DB
 * @var \Migration $migration
 */

$ADDTODISPLAYPREF[KnowbaseItem::class] = [79, 131, 13];

$table = 'glpi_knowbaseitems';

$field_to_add = 'entities_id';
if (!$DB->fieldExists($table, $field_to_add)) {
    $migration->addField(
        $table,
        $field_to_add,
        'fkey',
        [
            'after' => 'id'
        ]
    );
    $migration->addKey($table, $field_to_add);
}

$field_to_add = 'is_recursive';
if (!$DB->fieldExists($table, $field_to_add)) {
    $migration->addField(
        $table,
        $field_to_add,
        'bool',
        [
            'update' => 1,
            'after' => 'entities_id'
        ]
    );
    $migration->addKey($table, $field_to_add);
}
