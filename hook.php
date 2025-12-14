<?php

/**
 * Copyright (C) 2021  Mannheim University Library
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along
 * with this program. If not, see <http://www.gnu.org/licenses/>. 
 */

function plugin_wakeonlan_install() {
   $migration = new Migration(100);
   $migration->executeMigration();
   return true;
}

function plugin_wakeonlan_uninstall() {
   global $DB;

   $tables = [
      'configs'
   ];

   foreach ($tables as $table) {
      $tablename = 'glpi_plugin_wakeonlan_' . $table;
      if ($DB->tableExists($tablename)) {
         $DB->dropTable($tablename);
      }
   }

   return true;
}

function plugin_wakeonlan_postItemForm($params) {
   if (isset($params['item']) && $params['item'] instanceof CommonDBTM) {
      if (in_array(get_class($params['item']), ['Computer', 'Printer', 'Peripheral'])) {
         PluginWakeonlanWOL::show_button($params['item']);
      }
   }
}

function plugin_wakeonlan_MassiveActions($type) {
   $actions = [];
   if (in_array($type, ['Computer', 'Printer', 'Peripheral'])) {
      $myclass      = "PluginWakeonlanWOL";
      $action_key   = 'wake';
      $action_label = "Wake On LAN";
      $actions[$myclass.MassiveAction::CLASS_ACTION_SEPARATOR.$action_key] = $action_label;
   }
   return $actions;
}
