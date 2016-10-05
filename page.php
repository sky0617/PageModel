<?php

class PageManager{

	//檔案名稱
	public $file_name  = "";

	//總筆數
	public $total_rows = 0;

	//每頁筆數
	public $page_rows  = 10;

	//當前頁
	public $now_page   = 1;

	//總分頁數
	public $total_pages = 0;

	//每頁面顯示分頁數
	public $show_pages  = 10;

	//每頁面起始頁數
	public $show_start  = 0;

	//每頁面結束頁數
	public $show_end    = 0;

	//建構子
	public function __construct($_total_rows,$_page_rows,$_show_pages = 10,$_file_name = "",$_now_page = 1){

		$this->total_rows  = $_total_rows;
		$this->page_rows   = $_page_rows;
		$this->show_pages  = $_show_pages;
		$this->now_page    = $_now_page;
		$this->total_pages = ceil($this->total_rows/$this->page_rows);
		$this->file_name   = $_file_name;

		$this->_getShowPageInterval();

	}

	//解構子
	public function __destruct(){}

	//上N頁
	private function _getMorePrevPage(){

		$html = "";

		if($this->now_page > $this->show_pages){

			$more_prev_page = $this->show_start-$this->show_pages;
			$html .= "<a href=\"".$this->file_name."?page=".($more_prev_page)."\"> 上 ".$this->show_pages." 頁 </a>";

		}else{

			$html .= "<a href=\"javascript:;\"> 上 ".$this->show_pages." 頁 </a>";

		}

		return $html;

	}

	//下N頁
	private function _getMoreNextPage(){

		$html = "";

		if($this->total_pages > $this->show_pages && $this->show_start+$this->show_pages < $this->total_pages){

			$more_next_page = $this->show_start+$this->show_pages;
			$html .= "<a href=\"".$this->file_name."?page=".($more_next_page)."\"> 下 ".$this->show_pages." 頁 </a>";

		}else{

			$html .= "<a href=\"javascript:;\"> 下 ".$this->show_pages." 頁 </a>";

		}

		return $html;

	}

	//上一頁
	private function _getPrevPage(){

		$html = "";

		if($this->now_page != 1)
			$html .= "<a href=\"".$this->file_name."?page=".($this->now_page-1)."\"> 上 1 頁 </a>";
		else
			$html .= "<a href=\"javascript:;\"> 上 1 頁 </a>";

		return $html;

	}

	//下一頁
	private function _getNextPage(){

		$html = "";

		if($this->now_page < $this->total_pages)
			$html .= "<a href=\"".$this->file_name."?page=".($this->now_page+1)."\"> 下 1 頁 </a>";
		else
			$html .= "<a href=\"javascript:;\"> 下 1 頁 </a>";

		return $html;

	}

	//頁首
	private function _getStartPage(){

		$html = "";

		if($this->now_page != 1)
			$html .= "<a href=\"".$this->file_name."?page=1\"> 頁首 </a>";
		else
			$html .= "<a href=\"javascript:;\"> 頁首 </a>";

		return $html;

	}

	//頁尾
	private function _getEndPage(){

		$html = "";

		if($this->now_page != $this->total_pages)
			$html .= "<a href=\"".$this->file_name."?page=".$this->total_pages."\"> 頁尾 </a>";
		else
			$html .= "<a href=\"javascript:;\"> 頁尾 </a>";

		return $html;
		
	}

	//分頁數
	private function _getPages(){

		$html = "";

		for($i=1;$i<=$this->total_pages;$i++){

			if($i >= $this->show_start && $i <= $this->show_end){

				if($i == $this->now_page){

					$html .= "<a href=\"javascript:;\" style=\"color:red\"> ".$i." </a>";

				}else{

					$html .= "<a href=\"".$this->file_name."?page=".$i."\"> ".$i." </a>";

				}

			}

		}

		return $html;

	}

	//取得[每頁面起始頁數]&&[每頁面結束頁數]
	private function _getShowPageInterval(){

		$result = array();

		//當前頁 < 每頁面顯示分頁數

		if($this->now_page <= $this->show_pages){

			$this->show_start = 1;
			$this->show_end   = $this->show_pages;

		}else{

			//當前頁 > 每頁面顯示分頁數

			if($this->now_page%$this->show_pages != 0){

				$this->show_start = (floor($this->now_page/$this->show_pages)*$this->show_pages)+1;
				$this->show_end   = (floor($this->now_page/$this->show_pages)*$this->show_pages)+$this->show_pages;

			}else{

				$now_page         = $this->now_page-1;
				$this->show_start = (floor($now_page/$this->show_pages)*$this->show_pages)+1;
				$this->show_end   = (floor($now_page/$this->show_pages)*$this->show_pages)+$this->show_pages;

				if($this->show_end > $this->total_pages)
					$this->show_end = $this->total_pages;

			}

		}

	}

	public function _showPage(){

		$html = "";

		$html .= "<div>";

		$html .= $this->_getMorePrevPage();

		$html .= $this->_getPrevPage();

		$html .= $this->_getStartPage();

		$html .= $this->_getPages();

		$html .= $this->_getEndPage();

		$html .= $this->_getNextPage();

		$html .= $this->_getMoreNextPage();

		$html .= "</div>";

		return $html;

	}

	//Test
	public static function _test(){

		echo "test class success!"; 

	}

}

$page = isset($_GET['page']) && $_GET['page'] != "" ? intval($_GET['page']) : 1;

$PageObj = new PageManager(541,10,10,"page.php",$page);

$showPage = $PageObj->_showPage();

?>

<html>
	<head>
		<meta charset="utf-8">
		<style>
		a {
		    text-decoration:none;
		}
		</style>
	</head>
	<body>
		<?=$showPage?>
	</body>
</html>