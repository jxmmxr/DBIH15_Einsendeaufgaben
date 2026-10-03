<?php
file_put_contents('zaehler.txt', (int)file_get_contents('zaehler.txt') + 1);
?>