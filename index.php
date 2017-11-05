<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Latest News</title>
	<meta name="description" content="Demo for Uptime">
	<meta name="author" content="Rando Kuus">

	<link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
</head>

<body>
	<div id="header" class="container">
		<h1><span id="top-text">Latest</span> <span id="news-text">News</span></h1>
	</div>
<?php
	//get XML from provided URL
	function getXML($url){
		$xml = @simplexml_load_file($url);
		return $xml;
	}
	//get full article
	function getFullArticleData($url) {
	    $fetchUrl = 'https://mercury.postlight.com/parser?url='. $url;
	    $headers = array();
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_URL, $fetchUrl);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//MERCURY WEB PARSER API KEY
	    $headers[] = "x-api-key: VszVEApgyIXzHdJd0Z8NhFGHabR0mCjFMEDav6KV";
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
	    $data = curl_exec($ch);
	    if (curl_errno($ch)) {
	        echo 'Error:' . curl_error($ch);
	    }
	    curl_close ($ch);
	    return json_decode($data, true);
	}
	$rss = getXML('https://flipboard.com/@raimoseero/feed-nii8kd0sz?rss');
	if($rss){
	$index = 0;
	foreach($rss->channel->item as $item) {
		$item = get_object_vars($item);
		$article = getFullArticleData($item['link']);
		//shows if content is available
		if($article['content']){
		?>
		<div class="col-lg-4 col-sm-6 col-xs-12">
			<div class="item-row">
				<div class="item-container clearfix" data-toggle="modal" data-target="#item_modal_<?php echo $index;?>">
					<?php
					if($article['lead_image_url'] && $article['lead_image_url'] != 'None'):?>
						<img class="item-img" src="<?php echo $article['lead_image_url'];?>"/>
					<?php endif;?>
					<?php if($item['title']):?>
						<div class="item-title"><?php echo $item['title'];?></div>
					<?php endif;?>
					<?php if ($item['author']): ?>
						<div class="item-author"><?php  echo 'Author: ' . $item['author']; ?></div>
					<?php endif; ?>
					<?php if ($item['item-category']): ?>
						<div class="item-category"><?php echo 'Category: ' . $item['category']; ?></div>
					<?php endif; ?>
					<?php if ($item['description']): ?>
						<div class="modal-body item-description"><?php echo html_entity_decode($item['description']);?></div>
					<?php endif; ?>
					<?php if ($item['pubDate']): ?>
						<div class="item-published">Published at 
							<span><?php $d = new DateTime($item['pubDate']);
							echo $d->format('d-m-Y');
					?>
					</span>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<!-- if clicked, openes pop-up window -->
		<div class="modal" id="item_modal_<?php echo $index;?>" role="dialog">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo $article['title'];?></h4>
			  </div>
			  <!-- Shows article content in pop-up window -->
			  <div class="modal-body">
				<div><?php echo $article['content'];?></div>
			  </div>
			  <!--button to close article--> 
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  </div>
			</div>
		  </div>
		</div>
		<?php
		}
		$index++;
	}
	}
	//if no news is available
	else{
		?>
			<h3>Nothing to display at the moment. Please come back later.</h3>
		<?php
	}
?>
</body>
</html>
	