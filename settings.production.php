<?php

/**
 * @file
 * Configuración de Drupal para producción en ferter.es
 */

// Configuración de base de datos para producción
$databases['default']['default'] = [
  'database' => 'moneylink_prod',
  'username' => 'moneylink_user',
  'password' => 'TU_PASSWORD_AQUI',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'driver' => 'mysql',
  'charset' => 'utf8mb4',
  'collation' => 'utf8mb4_unicode_ci',
];

// Hash salt único para producción (generar nuevo)
$settings['hash_salt'] = 'GENERAR_HASH_SALT_UNICO_AQUI';

// Trusted host patterns
$settings['trusted_host_patterns'] = [
  '^ferter\.es$',
  '^www\.ferter\.es$',
];

// Configuración de producción
$config['system.logging']['error_level'] = 'hide';
$config['system.performance']['css']['preprocess'] = TRUE;
$config['system.performance']['js']['preprocess'] = TRUE;

// Directorio de archivos
$settings['file_public_path'] = 'sites/default/files';
$settings['file_private_path'] = '/var/www/vhosts/ferter.es/private_files';

// Configuración de cache
$settings['cache']['bins']['render'] = 'cache.backend.database';
$settings['cache']['bins']['page'] = 'cache.backend.database';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.database';

// Desactivar módulos de desarrollo
$config['system.performance']['cache']['page']['max_age'] = 900;

// Configuración de correo (ajustar según tu servidor)
$config['system.mail']['interface']['default'] = 'php_mail';