<?php
$url = parse_url(getenv("DATABASE_URL"));
$config = fopen("./config/config_db.php", "w") or die("Unable to open file!");

fwrite($config, "<?php\n");
fwrite($config, "class DB extends DBmysql\n");
fwrite($config, "{\n");
fwrite($config, "    public \$dbhost = " . $url['host'] . $url['port'] . ";\n");
fwrite($config, "    public \$dbuser = " . $url['user'] . ";\n");
fwrite($config, "    public \$dbpassword = " . $url['pass'] . ";\n");
fwrite($config, "    public \$dbdefault = " . substr($url['path'], 1) . ";\n");
fwrite($config, "    public \$use_utf8mb4 = true;\n");
fwrite($config, "    public \$allow_myisam = false;\n");
fwrite($config, "    public \$allow_datetime = false;\n");
fwrite($config, "    public \$allow_signed_keys = false;\n");
fwrite($config, "}\n\n");

fclose($config);
