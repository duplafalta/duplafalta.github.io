<?php
class FacePageAlbum
{
		private $URL;
		private $TOKEN;
		private $PAGE;
		private $PHOTOS;
		private function setPage()
		{
				$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
				$this->PAGE = $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		}

		private function setAlbumUrl($id)
		{
			if(is_numeric($id))
			{
				if($this->TOKEN)
				{
					$this->URL = "http://graph.facebook.com/".$id."/albums?".$this->TOKEN;
					return true;
				}
				else
				{
					$this->URL = "http://graph.facebook.com/".$id."/albums";
					return true;				
				}
			}
			else
			{
				return false;
			}
		}
		private function setToken($appId, $appSecret)
		{
			$this->TOKEN = $this->curlGetFile('https://graph.facebook.com/oauth/access_token?type=client_cred&client_id='.$appId.'&client_secret='.$appSecret);
		}
		
		public function FacePageAlbum($id, $albumId, $aurl, $appId, $appSecret)
		{
			$this->setPage();
			if($id)
			{
				if($appId && $appSecret)
					$this->setToken($appId, $appSecret);
				$this->setAlbumUrl($id);
				if($albumId && $this->albumChk($albumId))
				{
					$this->PHOTOS = 'http://graph.facebook.com/'.$albumId.'/photos';
					$json  = json_decode($this->curlGetFile($this->PHOTOS));
					if($json -> error) die("THERE HAS BEEN AN ERROR:album id invalid");
					echo '<a class="FBback" href="javascript:history.go(-1)">Voltar</a>';
					echo '<a class="FBbackAlbuns" href="'.$this->PAGE.'">Voltar para álbuns</a>';
					
					
					if($json->paging->previous)
						echo '<a class="FBprev" href="'.$PAGE.'?aurl='.urlencode($json->paging->previous).'">Voltar</a>';
					if($json->paging->next)
						echo '<a class="FBnext" href="'.$PAGE.'?aurl='.urlencode($json->paging->next).'">Próximo</a>';
					echo '<br clear="all" />';
					foreach($json->data as $v)
					{
						echo "<a class='ImageLink' href = '".$v->source."'><img class='thumbsA' src='".$v->picture."' /></a>";
					}
					return true;
				}
				else if ($aurl)
				{
					$this->PHOTOS = urldecode($aurl);
					$json  = json_decode($this->curlGetFile($this->PHOTOS));
					if($json -> error) die("THERE HAS BEEN AN ERROR: album url invalid");
					echo '<a class="FBback" href="javascript:history.go(-1)">Voltar</a>';
					echo '<a class="FBbackAlbuns" href="'.$this->PAGE.'">Voltar para álbuns</a>';
					if($json->paging->previous)
						echo '<a class="FBprev" href="'.$PAGE.'?aurl='.urlencode($json->paging->previous).'">Voltar</a>';
					if($json->paging->next)
						echo '<a class="FBnext" href="'.$PAGE.'?aurl='.urlencode($json->paging->next).'">Próximo</a>';
					echo '<br clear="all" />';
					foreach($json->data as $v)
					{
						echo "<a class='ImageLink' href = '".$v->source."'><img width='110px' src='".$v->picture."' /></a>";
					}
					return true;
				}
				else
				{
					$json = json_decode($this->curlGetFile($this->URL));
					if($json -> error) die("THERE HAS BEEN AN ERROR: pageId invalid");
					foreach($json->data as $v)
					{
					echo "<div class ='ImgWrapper'>";
					echo "<img src='https://graph.facebook.com/".$v->id."/picture' />";
						//echo  $v->from->name."<br>"; NOME DA PAGINA
						echo  "<a href = '".$this->PAGE;
						echo  "?";
						echo  "aid=".$v->id."'>".$v->name."</a>";
						echo  "<span>Fotos:".$v->count."</span><br>";
						echo "<br clear='all'></div>";
					}
					return true;
				}
			}
			return false;
		}
		public function curlGetFile($curlUrl)
		{
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $curlUrl); 
			$data = curl_exec($ch); 
			curl_close($ch);
			return $data;
		}
		
		public function albumChk($albumId)
		{
			$json = json_decode($this->curlGetFile($this->URL));
			$arrayId = array();
			foreach($json->data as $v)
						array_push($arrayId, $v->id);
			if(!in_array($albumId,$arrayId)) return false;
			return true;
		}
};
?>