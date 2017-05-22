<?php
file_put_contents('get.txt', json_encode($_GET));
file_put_contents('post.txt', json_encode($_POST));
echo '0000';
exit();