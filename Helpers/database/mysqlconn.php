<?php

/**
 *
 * This class will hold the mysql link statically to avoid creating multple connections
 *
 *
 */

class MysqlConn {

   public static $connections;

   static function __init()
   {
      if (!isset(self::$connections)) {
         self::$connections = array();
      }

   }

   public static function setConnection($connectionName, $connection) {
      self::$connections[$connectionName] = $connection;
   }

   public static function getConnection($connectionName='Default') {
      return isset(self::$connections[$connectionName]) ?
         self::$connections[$connectionName] : null;
   }

}
MysqlConn::__init();