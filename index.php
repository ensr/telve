<?php
	set_time_limit(0);
	ini_set('default_socket_timeout',300);
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	
	define("clientID", 'b951c5abc99e46e1b9d586ad8b439354');
	define("clientSecret", 'c3b20001e53648249b13f27637a53505');
	define("redirectURI", 'http://localhost/telve/index.php');
	define("imageDirectory", 'pics/');

	function connectToInstagram($url){
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => 2
		));
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}

	function printTagImages($tag){
		$url = 'https://api.instagram.com/v1/tags/'.$tag.'/media/recent?client_id='.clientID.'&count=30';
		$instagramInfo = connectToInstagram($url);
		$results = json_decode($instagramInfo, true);

		foreach($results['data'] as $items ){

			$image_url = $items['images']['low_resolution']['url'];
			$comments = $items['comments'];
			$ileti = $items['caption']['text'];
			$username = $items['caption']['from']['username'];

			echo $items['user']['username'].'</br>';
			echo '<img src="'.$image_url.'"/></br>';
			echo '<b>'.$username.'</b>:  '.$ileti.'</br>';
			
			foreach($comments['data'] as $commit){
				echo '<b>'.$commit['from']['username'].'</b>:  '.$commit['text'].'</br>';
			}
			echo '</br>';
		}
	
	}	
	

	if($_GET['code']){
		$tag = "istanbul";
		printTagImages($tag);

	}else{
?>
		<!doctype html>
		<html>
			<body>
				<a href="https://api.instagram.com/oauth/authorize/?client_id=<?php
				echo clientID; ?>&redirect_uri=<?php echo redirectURI;?>&response_type=code">Login</a>
			</body>
		</html>
<?php
	}
?>
