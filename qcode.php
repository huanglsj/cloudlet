<?php
include 'Application/Common/Org/Qrcode.class.php';
$data = $_GET['url'];//二维码内容
// 纠错级别：L、M、Q、H
$level = 'L';
// 点的大小：1到10,用于手机端4就可以了
$size = 4;

//输出图片
Header("Content-type: image/png");
QRcode::png($data, false, $level, $size);
