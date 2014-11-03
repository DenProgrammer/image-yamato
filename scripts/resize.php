<?php

echo "resize start\n";
$time_start = time();

$path = __DIR__ . '\..\\';
$dir  = opendir($path);

$fullchange = 0;
$sizechange = 0;
$notchange  = 0;
$allimages  = 0;
while ($file       = readdir($dir)) {
    $filepath = str_replace('\\', '/', $path . $file);
    if (is_file($filepath)) {
        $sizes = getimagesize($filepath);

        if ($sizes) {
            echo "$filepath\n";
            $allimages++;

            $width  = $sizes[0];
            $height = $sizes[1];

            $original = __DIR__ . '/../original/' . $file;
            $resized  = __DIR__ . '/../resized/' . $file;

            if ($width > 800 || $height > 600) {
                system("cp $filepath $original");
                system("convert -quality 90 -resize 800x600 $filepath $filepath");
                system("convert -quality 90 -resize 200x180 $filepath $resized");
                $fullchange++;
            } elseif (!is_file($resized)) {
                system("convert -quality 90 -resize 200x180 $filepath $resized");
                $sizechange++;
            } else {
                $notchange++;
            }
        }
    }
}
echo "allimages  = $allimages\n";
echo "fullchange = $fullchange\n";
echo "sizechange = $sizechange\n";
echo "notchange  = $notchange\n";

$time_end  = time();
$work_time = $time_end - $time_start;
echo "resize done -work time - $work_time s\n";
