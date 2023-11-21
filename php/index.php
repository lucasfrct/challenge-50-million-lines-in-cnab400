<?php
gc_collect_cycles();
for ($i=0; $i < 10; $i++) { 
    shell_exec("php write.php > /var/null &");
}