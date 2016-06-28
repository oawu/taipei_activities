# Welcome to 台北 • 藝文活動!
2016年，最新台北藝文活動！每天更新最新台北、新北所提供的藝文公告資訊，提供給大家最新的分類整理！

---

<br />
## 聲明
本作品授權採用 姓名標示-非商業性 2.0 台灣 (CC BY-NC 2.0 TW) 授權，詳見 [http://creativecommons.org/licenses/by-nc/2.0/tw/](http://creativecommons.org/licenses/by-nc/2.0/tw/) 

<br />
## DEMO
LIVE DEMO: [http://works.ioa.tw/taipei_activities/](http://works.ioa.tw/taipei_activities/)


<br />
## 說明
* 利用[新北市政府資料開放平台](http://data.ntpc.gov.tw/)提供的[新北市政府文化局藝文活動](http://data.ntpc.gov.tw/od/detail?oid=781B822E-214A-4B9A-B4DB-32C9F4626D98) [API](http://data.ntpc.gov.tw/od/data/api/A97AEE33-4109-457B-9FB1-DB754A0BB100;jsessionid=817A22A73AC4887777D95713CC89C5C8?$format=json) 所製作的[台北 • 藝文活動](http://works.ioa.tw/taipei_activities/)。
* 使用 [php](https://zh.wikipedia.org/zh-tw/PHP) 將 API 資料取下來後編輯成 [HTML](https://zh.wikipedia.org/zh-tw/HTML) 頁面，並且放置到 [Amazon S3](https://aws.amazon.com/tw/s3/)。放置部署過程中同時將頁面所需的 [css](https://zh.wikipedia.org/wiki/层叠样式表)、[JavaScript](https://zh.wikipedia.org/wiki/JavaScript) 一起上傳至 S3。
* 上傳 S3 過程採用 php 執行，關鍵程式碼[在這裡](https://github.com/comdan66/taipei_activities/blob/master/cmd/put.php)，主要是利用 S3 針對檔案都有 tag 的特性，對上傳檔案做 [md5_file](http://php.net/manual/en/function.md5-file.php)，達成差異化更新的步驟，步驟如下：
	1. 取得 S3 上所有檔案
	2. 整理準備上傳的檔案
	3. 比對準備上傳與 S3 上檔案的 md5_file 差異
	4. 針對差異做更新、刪除、上傳

* 網頁排版盡量參照 [Material Design](https://material.google.com/)，同時具有 [響應式網頁設計(RWD)](http://www.ibest.tw/page01.php) 的版型，讓手機用戶也可以方便瀏覽與輕鬆操作。
* 切版使用 [compass](http://compass-style.org/) 編譯 [scss](http://sass-lang.com/)，頁面上使用 JavaScript 完成互動功能，如：**快速搜尋**，利用 jQuery 的 [selector](https://api.jquery.com/category/selectors/) <b> [name*=”value”]</b> 完成模糊搜尋，並且利用網址的 [Hash](http://www.w3schools.com/jsref/prop_loc_hash.asp) 來做分類查詢。
* 依據資料開放平台上表示每天會更新，所以系統排程會在每日上午 6 時去取得最新的藝文活動資訊，並且放置到 s3 上做更新。
* 目前只爬取新北市政府的開放資料，未來會補上台北市的部分，若是有發現其他縣市的活動 API，也會一併整理起來。
* 若是覺得不錯，可以對 [GitHub](https://github.com/comdan66/taipei_activities) 按個星星，鼓勵一下作者吧：）