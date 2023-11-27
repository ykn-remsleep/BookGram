<?php

class rakutenbooks
{
	var $error; 
	var $hits; 
	var $webapi; 

	var $APPLICATIONID = '1090579900416049169'; 
	var $APPLICATION_SECRET = 'a2b4d94f8046ad9017bb5f1078e223e13807f7ab'; 
	var $AFFILIATEID = '2e6f87ca.8c27cac4.2e6f87cb.f0668e87'; 


	function __construct()
	{
		$this->error = FALSE;
		$this->errmsg = '';
		$this->hits = 0;
		$this->webapi = '';
	}

	
	function __destruct()
	{
		unset($this->items);
	}

		function iserror()
	{
		return $this->error;
	}

		function geterror()
	{
		return $this->errmsg;
	}

	
	function isphp5over()
	{
		$version = explode('.', phpversion());

		return $version[0] >= 5 ? TRUE : FALSE;
	}

	
	function callWebAPI($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}

	var $RakutenBooksItems = array(
		'title',
		//書籍タイトル
		'titleKana',
		//書籍タイトル カナ
		'subTitle',
		//書籍サブタイトル
		'subTitleKana',
		//書籍サブタイトル カナ
		'seriesName',
		//叢書名
		'seriesNameKana',
		//叢書名カナ
		'contents',
		//多巻物収録内容
		'contentsKana',
		//多巻物収録内容カナ
		'author',
		//著者名
		'authorKana',
		//著者名カナ
		'publisherName',
		//出版社名
		'size',
		//書籍のサイズ
		'isbn',
		//ISBNコード(書籍コード)
		'itemCaption',
		//商品説明文
		'salesDate',
		//発売日
		'itemPrice',
		//税込み販売価格
		'listPrice',
		//定価
		'discountRate',
		//割引率
		'discountPrice',
		//割引価格
		'itemUrl',
		//商品URL
		'affiliateUrl',
		//アフィリエイトURL
		'smallImageUrl',
		//商品画像 64x64URL
		'mediumImageUrl',
		//商品画像 128x128URL
		'largeImageUrl',
		//商品画像 200x200URL
		'chirayomiUrl',
		//チラよみURL
		'availability',
		//在庫状況
		'postageFlag',
		//送料フラグ
		'limitedFlag',
		//限定フラグ
		'reviewCount',
		//レビュー件数
		'reviewAverage',
		//レビュー平均
		'booksGenreId' //楽天ブックスジャンルID
	);

	
	function searchBooksURL($query, $author, $sort = 'standard')
	{
		$sort = urlencode($sort); 

		if (preg_match('/^[0-9]+$/', $query) > 0) { 
			$query = '&isbn=' . $query;
		} else if ($query != '') { 
						$query = preg_replace("/ー/ui", '-', $query);
			$query = '&title=' . urlencode($query);
		} else {
			$query = '';
		}
		if ($author != '')
			$author = '&author=' . urlencode($author);

		$appid = $this->APPLICATIONID;
		$affid = $this->AFFILIATEID;
		$res = "https://app.rakuten.co.jp/services/api/BooksBook/Search/20170404?applicationId={$appid}&affiliateId={$affid}&format=xml{$query}{$author}&sort={$sort}";

		return $res;
	}

		function searchBooks($query, $author, &$items, $sort = 'standard')
	{
		$url = $this->searchBooksURL($query, $author, $sort);
		if (($res = $this->callWebAPI($url)) == FALSE) {
			$this->error = TRUE;
			$this->errmsg = 'WebAPI呼び出しに失敗';
			return FALSE;
		}
		$this->webapi = $url;

		if ($this->isphp5over() == FALSE) {
			if (($dom = domxml_open_mem($res)) == NULL)
				return FALSE;
			$root = $dom->get_elements_by_tagname('root');

			$count = $root[0]->get_elements_by_tagname('count');
			$cnt = $count[0]->get_content();
			if ($cnt <= 0) {
				$this->error = TRUE;
				$this->errmsg = '検索結果なし';
				return FALSE;
			}
			
			$obj = $root[0]->get_elements_by_tagname('Items');
			$obj = $obj[0]->get_elements_by_tagname('Item');
			$cnt = 1;
			foreach ($obj as $val) {
				foreach ($this->RakutenBooksItems as $name) {
					$node = $val->get_elements_by_tagname($name);
					if ($node != NULL) {
						$items[$cnt][$name] = $node[0]->get_content();
					}
				}
				$items[$cnt]['title'] = preg_replace("/([あ-ん|ア-ン])-/ui", "$1ー", $items[$cnt]['title']);
				$items[$cnt]['titleKana'] = preg_replace("/([あ-ん|ア-ン])-/ui", "$1ー", $items[$cnt]['titleKana']);
				$cnt++;
			}

		} else {
			$xml = simplexml_load_string($res);
			
			$count = (int) $xml->count;
			if ($count <= 0) { 
				$this->error = TRUE;
				$this->errmsg = '検索結果なし';
				return FALSE;
			}
			$obj = $xml->Items->Item;
			$cnt = 1;
			foreach ($obj as $node) {
				foreach ($this->RakutenBooksItems as $name) {
					if (isset($node->$name)) {
						$items[$cnt][$name] = (string) $node->$name;
					}
				}
				$items[$cnt]['title'] = preg_replace("/([あ-ん|ア-ン])-/ui", "$1ー", $items[$cnt]['title']);
				$items[$cnt]['titleKana'] = preg_replace("/([あ-ん|ア-ン])-/ui", "$1ー", $items[$cnt]['titleKana']);
				$cnt++;
			}
		}
		$this->hits = $cnt - 1;

		return $this->hits;
	}

}

