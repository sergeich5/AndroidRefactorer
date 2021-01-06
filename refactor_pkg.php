<?php

do {
    echo "Что ищем: ";
    $find = trim(fgets(STDIN));
} while ($find == '');

do {
    echo "На что меняем: ";
    $replace = trim(fgets(STDIN));
} while ($replace == '');

echo PHP_EOL;
echo sprintf('Скрипт заменит во всех файлах "%s" на "%s"', $find, $replace).PHP_EOL;
echo sprintf("Все файлы в папке будут заменены").PHP_EOL;
echo PHP_EOL;
echo getcwd().PHP_EOL;
echo PHP_EOL;
echo "Запускаем?".PHP_EOL;
echo "yes для запуска, иное для отмены: ";

$answer = strtolower(trim(fgets(STDIN)));
if ($answer != "yes")
    die('Отмена'.PHP_EOL);

define("FIND", $find);
define("REPLACE", $replace);

scanDirectory(getcwd());

function refactorFile(string $filePath)
{
    file_put_contents(
        $filePath,
        str_replace(
            FIND,
            REPLACE,
            file_get_contents($filePath),
            $count
        )
    );
    if ($count > 0)
        echo sprintf('replaced %d times in %s', $count, $filePath).PHP_EOL;
}
function scanDirectory(string $path)
{
    logg(sprintf('scanning %s', $path), true);

    $files = scandir($path);
    foreach ($files as $fileName) {
        logg($fileName);

        $p = $path.DIRECTORY_SEPARATOR.$fileName;
        if (in_array($fileName, ['.', '..', basename(__FILE__)])) {
            logg(sprintf('skip %s', $p));
        }
        elseif (is_dir($p)) {
            logg(sprintf('dir %s', $p));
            if (in_array($fileName, ['.gradle', '.idea', 'build', '.git']))
                removeDir($p);
            else
                scanDirectory($p);
        }
        elseif (is_file($p)) {
            logg(sprintf('file %s', $p));
            if (in_array($fileName, ['google-services.json']))
                removeFile($p);
            else
                refactorFile($p);
        }
        logg();
    }
}
function removeFile(string $filePath)
{
    unlink($filePath);
    echo sprintf('removed file %s', $filePath).PHP_EOL;
}
function removeDir(string $dirPath)
{
    rrmdir($dirPath);
    echo sprintf('removed dir %s', $dirPath).PHP_EOL;
}
function rrmdir($dirPath) {
    $dir = opendir($dirPath);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $dirPath . DIRECTORY_SEPARATOR . $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($dirPath);
}
function logg(string $msg = '', bool $addEmptyLine = false)
{
    return;

    echo $msg.PHP_EOL;
    if ($addEmptyLine)
        echo PHP_EOL;
}
