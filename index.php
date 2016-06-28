<!DOCTYPE html>
<html lang="tw">
  <head>
    <meta http-equiv="Content-Language" content="zh-tw" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />
<?php
    if ($tags = array_unique ($keywords)) foreach ($tags as $i => $tag) if (!$i) { echo oa_meta (array ('property' => 'article:section', 'content' => $tag)); echo oa_meta (array ('property' => 'article:tag', 'content' => $tag)); } else echo oa_meta (array ('property' => 'article:tag', 'content' => $tag));
    if ($types) foreach ($types as $type) echo oa_meta (array ('property' => 'og:see_also', 'content' => $url . 'index.html' . '#' . rawurlencode (preg_replace ('/[\/%]/u', ' ', preg_replace ('/[\(\)]/u', '', $type))))); ?>
    <meta name="robots" content="index,follow" />
    <meta name="keywords" content="<?php echo implode (',', $tags);?>" />
    <meta name="description" content="<?php echo $description;?>" />
    <meta property="og:site_name" content="<?php echo $title;?>" />
    <meta property="og:url" content="<?php echo $url . 'index.html';?>" />
    <meta property="og:title" content="<?php echo $title;?>" />
    <meta property="og:description" content="<?php echo preg_replace ("/\s+/u", "", $description);?>" />
    <meta property="fb:admins" content="100000100541088" />
    <meta property="fb:app_id" content="199589883770118" />
    <meta property="og:locale" content="zh_TW" />
    <meta property="og:locale:alternate" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="article:author" content="https://www.facebook.com/comdan66" />
    <meta property="article:publisher" content="https://www.facebook.com/comdan66" />
    <meta property="article:modified_time" content="<?php echo date ('c');?>" />
    <meta property="article:published_time" content="<?php echo date ('c');?>" />
    <meta property="og:image" content="<?php echo $url . 'img/og.jpg';?>" alt="<?php echo $title;?>" />
    <meta property="og:image:type" tag="larger" content="image/jpg" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

    <link rel="canonical" href="/index.php" />
    <link rel="alternate" href="/index.php" hreflang="zh-Hant" />
    <link rel="canonical" href="<?php echo $url . 'index.html';?>" />
    <link rel="alternate" href="<?php echo $url . 'index.html';?>" hreflang="zh-Hant" />

    <title><?php echo $title;?></title>

    <link href="css/public.css" rel="stylesheet" type="text/css" />
    <script src="js/public.js" language="javascript" type="text/javascript" ></script>

  </head>
  <body lang="zh-tw">
    
    <header>
      <div>
        <a class='icon-m'></a>
        <h1><a href='<?php echo $url . 'index.html';?>'>台北 • 藝文活動</a></h1>
        <div>
          <a class='icon-s' title='分享至 Facebook'></a>
          <a href='http://www.ioa.tw/' class='icon-u' title='網站作者' target='_blank'></a>
        </div>
      </div>
    </header>

    <div id='container'>
      <div>
        <div>
    <?php if ($types) { ?>
            <span>分類</span>
            <ul>
              <li><a href='<?php echo $url . 'index.html';?>' class='a'>全部活動</a></li>
          <?php foreach ($types as $type) { ?>
                  <li><a data-name='<?php echo $type;?>' href='<?php echo $url . 'index.html' . '#' . rawurlencode (preg_replace ('/[\/%]/u', ' ', preg_replace ('/[\(\)]/u', '', $type)));?>'><?php echo $type;?></a></li>
          <?php }?>
            </ul>  
    <?php } ?>
          <span>開發</span>
          <ul>
            <li><a href='http://www.ioa.tw/' target='_blank'>開發人員</a></li>
            <li><a href='http://www.ioa.tw/activities-taipei.html' target='_blank'>製作說明</a></li>
            <li><a href='http://data.ntpc.gov.tw/od/detail?oid=781B822E-214A-4B9A-B4DB-32C9F4626D98' target='_blank'>資料來源</a></li>
            <li><a href='https://github.com/comdan66/activities.taipei' target='_blank'>GitHub</a></li>
          </ul>     
        </div>

        <div>
          <div class='condition'>
            <label><input type='search' id='search' placeholder='快速搜尋' /><i class='icon-h'></i></label>
            <label><input type='checkbox' id='src' value='src' checked /><span>只顯示有圖片</span></label>
            <div class='fb-like' data-href='<?php echo $url . 'index.html';?>' data-send='false' data-layout='button_count' data-action='like' data-show-faces='false' data-share='true'></div>
          </div>

          <div id='activities'>
      <?php if ($activities) {
              foreach ($activities as $activity) { ?>
                <div class='activity' data-type='<?php echo preg_replace ("/[^\x{4e00}-\x{9fa5}_a-zA-Z0-9]+/u", '', $activity['type']);?>' data-str='<?php echo 'all' . preg_replace ("/[^\x{4e00}-\x{9fa5}_a-zA-Z0-9]+/u", '', strtolower (strip_tags ($activity['author'] . $activity['title'] . $activity['type'] . $activity['description'])));?>' data-conditions='<?php echo $activity['src'] ? 'src': '';?>'>
                  <h2>
                    <a target="_blank"><?php echo $activity['title'];?></a>
                  </h2>
            <?php if ($activity['src']) { ?>
                    <a href="<?php echo $activity['link'];?>" target="_blank">
                      <img alt="" src="<?php echo $activity['src'];?>">
                    </a>
            <?php } ?>
                  <div class='description'><?php echo make_click_enable_link ($activity['description'], 50);?><a href='<?php echo $activity['link'];?>' target='_blank'></a></div>
                  <div class='info'>
                    <a href='https://www.google.com.tw/search?q=<?php echo rawurlencode (preg_replace ('/[\/%]/u', ' ', preg_replace ('/[\(\)]/u', '', $activity['author'])));?>' target='_blank'><?php echo $activity['author'];?></a>
                    <a href='<?php echo $url . 'index.html' . '#' . rawurlencode (preg_replace ('/[\/%]/u', ' ', preg_replace ('/[\(\)]/u', '', $activity['type'])));?>'><?php echo $activity['type'];?></a>
                  </div>
                </div>
        <?php }
            } else { ?>
              <div class='no_data'>目前還沒有任何活動！</div>
      <?php }?>
          </div>
        </div>
        <div class='cover'></div>
      </div>
    </div>
    
    <footer>
      <div></div>
      <div>
        <div><?php echo $title;?> ©<?php echo date ('Y');?></div>
        <div>如有相關問題歡迎與<a href="<?php echo 'http://www.ioa.tw/';?>" target="_blank">作者</a>討論。</div>
      </div>
      <div></div>
    </footer>

    <div id='fb-root'></div>
  </body>
</html>
