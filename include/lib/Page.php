<?php
/**
 * $p = new Paging;
 * $getpage = $_GET['page'];
 * $limit = 5;
 * $position = $p->searchPosition($limit, $getpage);
 * $totaldata = 20;
 * $totalpage = $p->totalPage($totaldata, $limit);
 * $linkPage = $p->navPage($getpage, $totalpage, "http://localhost", "category", "sport-title", "1", "Prev", "Next");
 * echo $linkPage;
 *
*/

class Paging
{

	function __construct(){}

	public function searchPosition($limit, $active_page)
	{
		if(empty($active_page)){
			$position = 0;
			$active_page = 1;
		}else{
			$position = ($active_page-1) * $limit;
		}
		return $position;
	}

	public function totalPage($totaldata, $limit)
	{
		$totalpage = ceil($totaldata/$limit);
		return $totalpage;
	}

	public function navPage($active_page, $totalpage, $website_url, $mod, $title, $pagetype, $prevtxt, $nexttxt)
	{
		$link_page = "";

		if ($active_page > 1) {
			$prev = $active_page-1;
			$link_page .= "<li><a href=\"{$website_url}{$mod}{$title}/{$prev}\">{$prevtxt}</a></li>";
		} else {
			$link_page .= "<li class=\"disabled\"><a>{$prevtxt}</a></li>";
		}

		if ($pagetype == "1") {
			$num = ($active_page > 3 ? "<li class=\"disabled\"><a>...</a></li>" : " ");
			for ($i=$active_page-2; $i<$active_page; $i++)
			{
				if ($i < 1)
				continue;
				$num .= "<li><a href=\"{$website_url}{$mod}{$title}/{$i}\">{$i}</a></li>";
			}
			$num .= "<li class=\"active\"><a>{$active_page}</a></li>";
			for ($i=$active_page+1; $i<($active_page+3); $i++)
			{
				if($i > $totalpage)
				break;
				$num .= "<li><a href=\"{$website_url}{$mod}{$title}/{$i}\">{$i}</a></li>";
			}
			$num .= ($active_page+2<$totalpage ? "<li class=\"disabled\"><a>...</a></li><li><a href=\"{$website_url}{$mod}/{$title}/{$totalpage}\">{$totalpage}</a></li>" : " ");
			$link_page .= "{$num}";
		}

		if ($active_page < $totalpage) {
			$next = $active_page+1;
			$link_page .= "<li><a href=\"{$website_url}{$mod}{$title}/{$next}\">{$nexttxt}</a></li>";
		} else {
			$link_page .= "<li class=\"disabled\"><a>{$nexttxt}</a></li>";
		}
		return $link_page;
	}

}