<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Music Viewer</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link href="viewer.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div id="header">

			<h1>190M Music Playlist Viewer</h1>
			<h2>Search Through Your Playlists and Music</h2>
		</div>


		<div id="listarea">
			<?php
				$songs = glob("songs/*.mp3");
				if(isset($_GET['playlist']) && $_GET['playlist'] != "") {
						$mode = 1;
						$playlist = $_GET['playlist'];
						$playlist = file_get_contents("songs/".$playlist);
						$raw = explode(PHP_EOL, $playlist);
						$songs = [];
						foreach($raw as $line) {
							if(substr($line, 0, 1) !== "#" && $line !== "") {
								array_push($songs, $line);
							}
								
						}
						echo "<a class=\"button\" href=\"music.php\">Go back!</a>";
				}
				if(isset($_GET['shuffle']) && $_GET['shuffle'] == "on") {
					shuffle($songs);
				}
				if(isset($_GET['bysize']) && $_GET['bysize'] == "on") {
					$sort = true;
				}
			?>
			<ul id="musiclist">
				<?php
					if($mode == 1) {
						foreach($songs as $song) {
							echo "<li class=\"mp3item\">"
								."<a href=\"songs/".$song."\">"
								.$song."</a>"
								."</li>";
						}
					} 
					else {
						if($sort)
							usort($songs, function($a, $b) {
								return filesize($a) < filesize($b);
							});
						foreach($songs as $song) {
							$size = filesize($song);
							if($size < 1023)
								$size = $size."B";
							else if($size < 1048575)
								$size = round($size/1024, 2)."KB";
							else
								$size = round($size/1048576, 2)."MB";
							echo "<li class=\"mp3item\">"
								."<a href=\"".$song."\">"
								.basename($song)."</a>"
								."(".$size.")"
								."</li>";
						}
						foreach(glob("songs/*.m3u") as $file) {
							echo "<li class=\"playlistitem\">"
								."<a href=\"?playlist=".basename($file)."\">"
								.basename($file)."</a>"
								."</li>";
						}
					}
				?>
				
		
			</ul>
		</div>
	</body>
</html>
