<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

date_default_timezone_set ('Asia/Taipei');
mb_internal_encoding ('UTF-8');
mb_regex_encoding ("UTF-8");

include_once 'libs/functions.php';

define ('PROTOCOL', "http://");
define ('FCPATH', implode (DIRECTORY_SEPARATOR, explode (DIRECTORY_SEPARATOR, dirname (str_replace (pathinfo (__FILE__, PATHINFO_BASENAME), '', __FILE__)))) . '/');
define ('NAME', ($temps = array_filter (explode (DIRECTORY_SEPARATOR, FCPATH))) ? end ($temps) : '');
if (!NAME) {
  echo "\n" . str_repeat ('=', 80) . "\n";
  echo ' ' . color ('◎', 'R') . ' ' . color ('錯誤囉！', 'r') . color ('請確認常數 NAME 是否正確，請洽詢設計者', 'p') . ' ' . color ('OA Wu', 'W') . '(http://www.ioa.tw)' . color ('！', 'p') . '  ' . color ('◎', 'R');
  echo "\n" . str_repeat ('=', 80) . "\n\n";
  exit ();
}

$file = array_shift ($argv);
$argv = params ($argv, array (array ('-b', '-bucket'), array ('-a', '-access'), array ('-s', '-secret')));
if (!(isset ($argv['-b'][0]) && ($bucket = trim ($argv['-b'][0], '/')) && isset ($argv['-a'][0]) && ($access = $argv['-a'][0]) && isset ($argv['-s'][0]) && ($secret = $argv['-s'][0]))) {
  echo "\n" . str_repeat ('=', 80) . "\n";
  echo ' ' . color ('◎', 'R') . ' ' . color ('錯誤囉！', 'r') . color ('請確認參數是否正確，分別需要', 'p') . ' ' . color ('-b', 'W') . '、' . color ('-a', 'W') . '、' . color ('-s', 'W') . ' ' . color (' 的參數！', 'p') . ' ' . color ('◎', 'R');
  echo "\n" . str_repeat ('=', 80) . "\n\n";
  exit ();
}

$prevs = array (
  array (
      'path' => '..',
      'formats' => array ('html', 'txt'),
      'list' => true,
    ),
  array (
      'path' => '../css',
      'formats' => array ('css'),
    ),
  array (
      'path' => '../js',
      'formats' => array ('js'),
    ),
  array (
      'path' => '../font',
      'formats' => array ('eot', 'svg', 'ttf', 'woff'),
    ),
  array (
      'path' => '../img',
      'formats' => array ('png', 'jpg', 'jpeg', 'gif', 'svg')
    ),
);


echo "\n" . str_repeat ('=', 80) . "\n";
echo ' ' . color ('◎ 執行開始 ◎', 'P') . "\n";
echo str_repeat ('-', 80) . "\n";

$url = 'http://data.ntpc.gov.tw/od/data/api/A97AEE33-4109-457B-9FB1-DB754A0BB100?$format=json';
$data = array ();

include_once 'libs' . DIRECTORY_SEPARATOR . 'phpquery.php';

if (!$activities = urldecode (file_get_contents ($url))) {
  echo ' ➜ ' . color ('API 取得資料失敗..', 'r') . "\n";
  echo str_repeat ('-', 80) . "\n";
  echo ' ' . color ('◎ 執行結束 ◎', 'P') . "\n";
  echo str_repeat ('=', 80) . "\n";
  echo "\n";
  exit ();
}

echo ' ➜ ' . color ('取得 API 資料內容(0)', 'g') . ' -    0% ';
$i = 0;
$c = count ($activities = json_decode ($activities, true));
$activities = array_map (function ($activity) use (&$i, $c) {
  $src = ($data = urldecode (file_get_contents ($activity['link']))) && ($php_query = phpQuery::newDocument ($data)) && ($content = pq ('#ctl00_ContentPlaceHolder1_panContent1', $php_query)) && ($imgs = pq ('img', $content)) && $imgs->length ? $imgs->attr ('src') : '';
  $src = $src && !preg_match ('/^http/', $src) ? !(preg_match ('/^\//', $src) && ($uri = parse_url ($activity['link']))) ? pathinfo ($activity['link'], PATHINFO_DIRNAME) . '/' . $src : preg_replace ('/^\//', $uri['scheme'] . '://' . $uri['host'] . '/', $src) : $src;
  $start_at = datetime ($activity['startdata']);
  $end_at = datetime ($activity['enddata']);
  $public_at = preg_replace_callback ('/(\d{3})-(\d{1,2})-(\d{1,2})\s+(\d{1,2}):(\d{1,2}):(\d{1,2})/', function ($matches) { return date ('Y-m-d H:i:s', strtotime (($matches[1] + 1911) . '-' . sprintf ('%02d', $matches[2]) . '-' . sprintf ('%02d', $matches[3]) . ' ' . sprintf ('%02d', $matches[4]) . ':' . sprintf ('%02d', $matches[5]) . ':' . sprintf ('%02d', $matches[6]))); },  $activity['pubDate']);

  echo sprintf ("\r ➜ " . color ('取得 API 資料內容', 'g') . color ('(' . $c . ')', 'g') . " - % 3d%% ", ceil ((++$i * 100) / $c));

  $description = remove_ckedit_tag ($activity['description']);
  $description = !$description ? $activity['title'] . ' 是由 ' . $activity['author'] . '於 ' . $public_at . ' 發佈的 ' . $activity['type'] . '活動。活動期間為 ' . date ('Y-m-d', strtotime ($start_at)) . '~' . date ('Y-m-d', strtotime ($end_at)) . '，相關藝文活動訊息可參考: ' . $activity['link'] : $description;
  return array (
      'title' => $activity['title'],
      'description' => $description,
      'link' => $activity['link'],
      'type' => $activity['type'],
      'src' => $src,
      'author' => $activity['author'],
      'start_at' => $start_at,
      'end_at' => $end_at,
      'public_at' => $public_at,
    );
}, $activities);
echo "- " . color ('完成取得資料！', 'C') . "\n";
echo str_repeat ('-', 80) . "\n";

$types = preg_replace ("/[^\x{4e00}-\x{9fa5}_a-zA-Z0-9]+/u", '', array_unique (column_array ($activities, 'type')));
$keywords = array_unique (array_merge (array ('藝文活動', '台北藝文活動', '台北藝文', '新北藝文', '雙北藝文', '新北藝文活動', '藝文'), $types));
$description = '台北' . date ('Y') . '年最新藝文活動都在這裡！利用新北市政府資料開放平台的 API 所製作的台北藝文活動網頁，讓大家可以輕鬆瀏覽以及獲得台北藝文活動相關訊息！此網站事使用 php 將 API 資料取下來後編輯成 HTML 頁面，並且放置到 Amazon S3。再利用 JavaScript 實作模糊搜尋功能。系統排程會在每日上午 6 時去取得最新的藝文活動資訊，並且放置到 S3 上做更新讓大家可以每天一早就獲得最新的訊息喔！';

$view = load_view ('root' . DIRECTORY_SEPARATOR . 'index.php', array (
    'title' => '台北 • 藝文活動',
    'url' => PROTOCOL . $bucket . '/' . NAME . '/',
    'types' => $types,
    'keywords' => $keywords,
    'description' => $description,
    'activities' => $activities,
  ));

if (!write_file ('root' . DIRECTORY_SEPARATOR . 'index.html', preg_replace (array ('/\>[^\S ]+/su', '/[^\S ]+\</su', '/(\s)+/su'), array ('>', '<', '\\1'), $view)))
  echo ' ➜ ' . color ('寫入 HTML 失敗..', 'r') . "\n";
else
  echo ' ➜ ' . color ('寫入 HTML 完成！', 'g') . "\n";
echo str_repeat ('-', 80) . "\n";


echo ' ➜ ' . color ('初始化 S3 工具', 'g');

include_once 'libs/s3.php';
S3::init ($access, $secret);
echo ' - ' . color ('初始化成功！', 'C') . "\n";
echo str_repeat ('-', 80) . "\n";


echo ' ➜ ' . color ('列出 S3 上所有檔案', 'g');
try {
  $s3_files = array_filter (S3::getBucket ($bucket, NAME), function ($s3_file) {
    return preg_match ('/^' . NAME . '\//', $s3_file['name']);
  });
  echo color ('(' . ($c = count ($s3_files)) . ')', 'g') . ' - 100% - ' . color ('取得檔案成功！', 'C') . "\n";
  echo str_repeat ('-', 80) . "\n";
} catch (Exception $e) {
  echo ' - ' . color ('取得檔案失敗！', 'R') . "\n";
  exit ();
}

$i = 0;
$local_files = array ();
$c = count ($prevs);
echo ' ➜ ' . color ('列出即將上傳所有檔案', 'g');

$local_files = array_2d_to_1d (array_map (function ($prev) use ($c, &$i) {
    $files = array ();
    $func = isset ($prev['list']) && $prev['list'] ? 'directory_list' : 'directory_map';
    merge_array_recursive ($func ($prev['path']), $files, $prev['path']);
    $files = array_filter ($files, function ($file) use ($prev) { return in_array (pathinfo ($file, PATHINFO_EXTENSION), $prev['formats']); });
    echo "\r ➜ " . color ('列出即將上傳所有檔案', 'g') . ' - ' . sprintf ('% 3d%% ', (100 / $c) * ++$i);
    return array_map (function ($file) { return array ('path' => $file, 'md5' => md5_file ($file), 'uri' => preg_replace ('/^(\.\.\/)/', '', $file)); }, $files);
 }, $prevs));

echo ' ➜ ' . color ('過濾需要上傳檔案', 'g');
$i = 0;
$c = count ($local_files);
$upload_files = array_filter ($local_files, function ($local_file) use ($s3_files, &$i, $c) {
  foreach ($s3_files as $s3_file)
    if (($s3_file['name'] == (NAME . DIRECTORY_SEPARATOR . $local_file['uri'])) && ($s3_file['hash'] == $local_file['md5']))
      return false;
  echo sprintf ("\r" . ' ➜ ' . color ('過濾需要上傳檔案', 'g') . color ('(' . ($i + 1) . ')', 'g') . " - % 3d%% ", ceil ((++$i * 100) / $c));
  return $local_file;
});
echo sprintf ("\r" . ' ➜ ' . color ('過濾需要上傳檔案', 'g') . color ('(' . count ($upload_files) . ')', 'g') . " - % 3d%% ", 100);
echo '- ' . color ('過濾需要上傳檔案成功！', 'C') . "\n";
echo str_repeat ('-', 80) . "\n";


echo sprintf ("\r" . ' ➜ ' . color ('上傳檔案', 'g') . color ('(' . ($c = count ($upload_files)) . ')', 'g') . " - % 3d%% ", $c ? ceil ((++$i * 100) / $c) : 100);
$i = 0;
if (array_filter (array_map (function ($file) use ($bucket, &$i, $c) {
  echo sprintf ("\r" . ' ➜ ' . color ('上傳檔案', 'g') . color ('(' . $c . ')', 'g') . " - % 3d%% ", ceil ((++$i * 100) / $c));
  try {
    return !S3::putFile ($file['path'], $bucket, NAME . DIRECTORY_SEPARATOR . $file['uri']);
  } catch (Exception $e) {
    return true;
  }
}, $upload_files))) {
  echo '- ' . color ('上傳發生錯誤！', 'r') . "\n";
  echo str_repeat ('=', 80) . "\n";
  return;
}
echo '- ' . color ('上傳成功！', 'C') . "\n";
echo str_repeat ('-', 80) . "\n";


echo ' ➜ ' . color ('過濾需要刪除檔案', 'g');
$i = 0;
$c = count ($s3_files);
$delete_files = array_filter ($s3_files, function ($s3_file) use ($local_files, &$i, $c) {
  foreach ($local_files as $local_file) if ($s3_file['name'] == (NAME . DIRECTORY_SEPARATOR . $local_file['uri'])) return false;
  echo sprintf ("\r" . ' ➜ ' . color ('過濾需要刪除檔案', 'g') . color ('(' . ($i + 1) . ')', 'g') . " - % 3d%% ", ceil ((++$i * 100) / $c));
  return true;
});
echo sprintf ("\r" . ' ➜ ' . color ('過濾需要刪除檔案', 'g') . color ('(' . count ($delete_files) . ')', 'g') . " - % 3d%% ", 100);
echo '- ' . color ('過濾需要刪除檔案成功！', 'C') . "\n";
echo str_repeat ('-', 80) . "\n";


echo sprintf ("\r" . ' ➜ ' . color ('刪除 S3 上需要刪除的檔案(' . ($c = count ($delete_files)) . ')', 'g'));
$i = 0;
echo '- ' . (array_filter (array_map (function ($file) use ($bucket, &$i, $c) {
  echo sprintf ("\r" . ' ➜ ' . color ('刪除 S3 上需要刪除的檔案(' . $c . ')', 'g') . " - % 3d%% ", ceil ((++$i * 100) / $c));
  return !S3::deleteObject ($bucket, $file['name']);
  try {
    return !S3::deleteObject ($bucket, $file['name']);
  } catch (Exception $e) {
    return true;
  }
}, $delete_files)) ? color ('刪除 S3 上需要刪除的檔案失敗！', 'r') : color ('刪除 S3 上需要刪除的檔案成功！', 'C')) . "\n";
echo str_repeat ('-', 80) . "\n";


echo ' ' . color ('◎ 執行結束 ◎', 'P') . "\n";
echo str_repeat ('=', 80) . "\n";
echo "\n";

echo " " . color ('➜', 'R') . " " . color ('您的網址是', 'G') . "：" . color (PROTOCOL . $bucket . '/' . NAME . '/', 'W') . "\n\n";
echo str_repeat ('=', 80) . "\n";
echo "\n";
