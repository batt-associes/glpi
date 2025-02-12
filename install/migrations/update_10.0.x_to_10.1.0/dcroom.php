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

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * @var \DBmysql $DB
 * @var \Migration $migration
 */

if (!$DB->fieldExists("glpi_dcrooms", "vis_cell_width")) {
    $migration->addField(
        "glpi_dcrooms",
        "vis_cell_width",
        "int",
        [
            'after'  => "vis_rows",
            'value'  => 40,
        ]
    );
}
if (!$DB->fieldExists("glpi_dcrooms", "vis_cell_height")) {
    $migration->addField(
        "glpi_dcrooms",
        "vis_cell_height",
        "int",
        [
            'after'  => "vis_cell_width",
            'update' => 39,
            'value'  => 40,
        ]
    );
}
