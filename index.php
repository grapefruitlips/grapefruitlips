<?
error_reporting(E_ALL);
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
	
	if(isset($_POST['login'])) {
		
	$login = test_input($_POST["login"]);
	$pass = test_input($_POST["password"]);
	
		if($login=="admin"&&$pass=="123") {
			$_SESSION['logged_in']=1;
			header("Location: /index.php?ok");
		}else{
			header("Location: /index.php?error=badlogin");
		}


	}elseif(isset($_POST['email']) && isset($_POST['username']) && isset($_POST['text'])){
		
	$filename = 'data.txt';	
		
	$username = test_input($_POST["username"]);
	$email = test_input($_POST["email"]);
	$text = test_input($_POST["text"]);
	
	$arr=array();
	$arr['username']=$username;
	$arr['email']=$email;
	$arr['text']=$text;
	$arr['status']=0;
	$arr['editedbyadm']=0;
	
	$f = fopen($filename, 'rb'); $lines = 0; while (!feof($f)) {$lines += substr_count(fread($f, 8192), "\n");} fclose($f);
	$arr['id']=$lines+1;

		
		$data = serialize($arr); 
		file_put_contents($filename, $data."\n", FILE_APPEND | LOCK_EX);
		header("Location: /index.php?added");
			
	}elseif(isset($_POST['edit'])){
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==1) {
		$edit_id=preg_replace("/[^0-9]/", "", $_POST['edit']);
		$text = test_input($_POST["text"]);
		$filedata = file("data.txt");
			$ar=array();
			foreach($filedata AS $line) {
				$ar[] = unserialize($line);
			}
			file_put_contents("data.txt","", LOCK_EX);
			foreach ($ar AS $k=>$v){
					if($ar[$k]['id']==$edit_id){
					if($ar[$k]['text']!=$text){
					$ar[$k]['text']=$text;
					$ar[$k]['editedbyadm']=1;
					}
						if(isset($_POST['status'])) {
						$ar[$k]['status']=1;
						}else{
						$ar[$k]['status']=0;	
						}
					//echo print_r($ar);
				}
				$newdata = serialize($ar[$k]); 
				file_put_contents("data.txt", $newdata."\n", FILE_APPEND | LOCK_EX);
			}
			header("Location: /index.php?edited=".$edit_id);
		}else{
			header("Location: /index.php?error=badlogin");
		}
	}
	
	
}else{
	
	
	if(!isset($_SESSION['sort']) && !isset($_SESSION['sortby'])) {
		$_SESSION['sort']="username";
		$_SESSION['sortby']=SORT_ASC;
	}
	

	if(isset($_GET['sort']) && $_GET['sort']=="username") { 
	$_SESSION['sort']="username";
	}else if(isset($_GET['sort']) && $_GET['sort']=="email") { 
	$_SESSION['sort']="email";
	}else if(isset($_GET['sort']) && $_GET['sort']=="status") { 
	$_SESSION['sort']="status";
	}
	
	if(isset($_GET['sortby']) && $_GET['sortby']=="SORT_ASC") { 
	$_SESSION['sortby']=SORT_ASC;
	}else if(isset($_GET['sortby']) && $_GET['sortby']=="SORT_DESC") { 
	$_SESSION['sortby']=SORT_DESC;
	}

if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==1) {
	$logged_in=1;
}else{
	$logged_in=0;
}
	
	if(isset($_GET['logout'])) {
		$_SESSION['logged_in']=0;
		$logged_in=0;
	}

$page = ! empty( $_GET['page'] ) ? (int) $_GET['page'] : 1; //preg_replace("/[^0-9]/", "", $_GET['page']);

?><!DOCTYPE html><html>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Test</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<style type="text/css">a:link, a:visited{color:#324348}a:hover, a:active{color:#000}#site{padding:0 20px}.mxw{max-width:980px; margin:0 auto} .form-inline{margin:0 -15px}.mrgb{ margin-bottom:20px}.navbar{ margin-top:20px;background: #fff;} .grbg{background-color: #f2f4f5} .txar{float:right}.clr{clear:both}.fa-sort{color: #c7c9d0}.editedbyadm{color: #a1b4c5!important;font-style: italic;padding-left: 10px;float: right}small.jobdone{color: #008c04}small.jobundone{color: #c1abab}li.page-item.act a:link, li.page-item.act a:visited{color: #8f929e;background-color: #ebf2fb;border-color: #d5dce6}small.right{float: right;padding-right: 15px;font-size: 16px;color: #adaeb5;line-height: 35px}.ednm {padding: 0 0 15px}.mrgbt{margin-bottom:15px}</style>
</head> 
<body>

<div id="site">
<nav class="navbar navbar-light mxw mrgb">
  <form class="form-inline">
    <? if(isset($_GET['edit'])) { ?><a class="btn btn-outline-secondary mr-sm-2" href="/index.php">Главная</a><? } ?><button class="btn btn-success mr-sm-2" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><? if(isset($_GET['edit'])) { ?>Изменить<? } else { ?>Добавить<? } ?> задачу</button>
    <? if($logged_in==0){?><button class="btn btn-outline-secondary" type="button"  data-toggle="collapse" data-target="#collapseLogin" aria-expanded="false" aria-controls="collapseLogin">Авторизация</button><? } 
	else { ?><a class="btn btn-outline-secondary" href="/index.php?logout">Выход</a><? } ?>
  </form>
</nav>

<div class="collapse <? if(isset($_GET['error'])) { ?>show <?} ?>mxw mrgb" id="collapseLogin">
  <div class="card card-body grbg">
<form method="post">
  <div class="form-group">
    <input type="text" name="login" class="form-control" id="exampleInputUsername" aria-describedby="emailHelp" placeholder="Имя пользователя">
  </div>
<div class="form-group">
    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Пароль">
  </div>
  <? if(isset($_GET['error'])) { ?>
  <div class="form-group">
  <small class="form-text text-muted"><span class="text-danger">Неправильные имя пользователя или пароль.</span></small>
    </div>
  <? } ?>
  <button type="submit" class="btn btn-primary">Войти</button>
</form>
  </div>
</div>
<? 
$edit=0;
if(isset($_GET['edit']) && $logged_in==1) {
$edit=1;
$filedata = file("data.txt");$ar=array();foreach($filedata AS $line) {$ar[] = unserialize($line);}
	foreach ($ar AS $k=>$d) {
		if($d['id']==$_GET['edit']) {
			$edit_username=$d['username'];
			$edit_email=$d['email'];
			$edit_status=$d['status'];
			$edit_text=$d['text'];
			$edit_id=$d['id'];
		}
	}

}
?>
<div class="collapse <? if(isset($_GET['edit']) && $logged_in==1) {?>show <? } ?>mxw mrgb" id="collapseExample">
  <div class="card card-body grbg">
<form method="post">
  <? if($edit==1){?>
  <input type="hidden" name="edit" value="<? echo $edit_id; ?>"/>
  <div class="ednm"><b><? echo $edit_username; ?></b> 
  <? echo $edit_email; ?></div>
  <?}else{?>
  <div class="form-group">
	<label for="exampleInputUsername">Введите имя пользователя</label>
    <input type="text" name="username" class="form-control" id="exampleInputUsername" aria-describedby="emailHelp" placeholder="Имя пользователя">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Ваш E-mail адрес</label>
    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="my@mail.com">
  </div>
  <? } ?>
 <div class="form-group">
    <label for="exampleFormControlTextarea1">Описание задачи</label>
    <textarea class="form-control" name="text" id="exampleFormControlTextarea1" rows="3"><? if($edit==1) { echo $edit_text; }?></textarea>
  </div>
  <? if($edit==1){?>
  <div class="form-check mrgbt">
    <input type="checkbox" name="status" class="form-check-input" id="exampleCheck1"<? if($edit_status==1){?> checked<?}?>>
    <label class="form-check-label" for="exampleCheck1">Выполнено</label>
  </div>
  <? } ?>
  <button type="submit" class="btn btn-primary"><? if($edit==1){?>Изменить<? } else { ?>Отправить<? } ?></button>
</form>
  </div>
</div>

<div class="mxw">
<nav class="txar">
  <ul class="pagination">
    <li class="page-item<? if($_SESSION['sort']=="username"){?> act<? } ?>"><a class="page-link" href="/index.php?page=<? echo $page; ?>&sort=username&sortby=<? if($_SESSION['sortby']==SORT_ASC){?>SORT_DESC<? } else { ?>SORT_ASC<? } ?>">Имя пользователя <i class="fa fa-fw fa-sort <? if($_SESSION['sort']=="username"&&$_SESSION['sortby']==SORT_ASC){?>fa-sort-asc<? } else if($_SESSION['sort']=="username"){?>fa-sort-desc<?}?>"></i></a></li>
    <li class="page-item<? if($_SESSION['sort']=="email"){?> act<? } ?>"><a class="page-link" href="/index.php?page=<? echo $page; ?>&sort=email&sortby=<? if($_SESSION['sortby']==SORT_ASC){?>SORT_DESC<? } else { ?>SORT_ASC<? } ?>">E-mail <i class="fa fa-fw fa-sort <? if($_SESSION['sort']=="email"&&$_SESSION['sortby']==SORT_ASC){?>fa-sort-asc<? } else if($_SESSION['sort']=="email"){?>fa-sort-desc<?}?>"></i></a></li>
    <li class="page-item<? if($_SESSION['sort']=="status"){?> act<? } ?>"><a class="page-link" href="/index.php?page=<? echo $page; ?>&sort=status&sortby=<? if($_SESSION['sortby']==SORT_ASC){?>SORT_DESC<? } else { ?>SORT_ASC<? } ?>">Статус <i class="fa fa-fw fa-sort <? if($_SESSION['sort']=="status"&&$_SESSION['sortby']==SORT_ASC){?>fa-sort-asc<? } else if($_SESSION['sort']=="status"){?>fa-sort-desc<?}?>"></i></a></li>
  </ul>
</nav>
<small class="right">Сортировка:</small>
</div><div class="clr"></div>
<?

$filedata = file("data.txt");
$ar=array();
foreach($filedata AS $line) {
	$ar[] = unserialize($line);
}

function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) { 
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }
    array_multisort($sort_col, $dir, $arr);
}
array_sort_by_column($ar, $_SESSION['sort'], $_SESSION['sortby']);

class pagination{
    var $page = 1; 
    var $perPage = 3; 
    var $showFirstAndLast = false; 
    function generate($array, $perPage=3){
      if (!empty($perPage))
        $this->perPage = $perPage;
      if (!empty($_GET['page'])) {
        $this->page = (int) $_GET['page'];
      } else {
        $this->page = 1; 
      }
      $this->length = count($array);
      $this->pages = ceil($this->length / $this->perPage);
      $this->start  = ceil(($this->page - 1) * $this->perPage);
      return array_slice($array, $this->start, $this->perPage);
    }
    function links(){$plinks = array();$links = array(); $slinks = array();
      if (count($_GET)) {
        $queryURL = '';
        foreach ($_GET as $key => $value) {
          if ($key != 'page') {
            $queryURL .= '&'.$key.'='.$value;
          }
        }
      }else{
		  $queryURL='';
	  }
      if (($this->pages) > 1) {
        if ($this->page != 1) {
          if ($this->showFirstAndLast) {
            $plinks[] = '<li class="page-item"><a class="page-link" href="?page=1'.$queryURL.'">&laquo;&laquo; Первая</a></li>';   
          }
          $plinks[] = '<li class="page-item"><a class="page-link" href="?page='.($this->page - 1).$queryURL.'">&laquo;&laquo; Предыдущая</a></li>';   
        }
        for ($j = 1; $j < ($this->pages + 1); $j++) {
          if ($this->page == $j) {
            $links[] = '<li class="page-item disabled active"><a class="page-link" href="?page='.$j.$queryURL.'">'.$j.'</a></li>'; 
          } else {
            $links[] =  '<li class="page-item"><a class="page-link" href="?page='.$j.$queryURL.'">'.$j.'</a></li>';  
          }
        }
        if ($this->page < $this->pages) {
          $slinks[] =  '<li class="page-item"><a class="page-link" href="?page='.($this->page + 1).$queryURL.'">Следующая &raquo;</a></li>';   
          if ($this->showFirstAndLast) {
            $slinks[] = '<li class="page-item"><a class="page-link" href="?page='.($this->pages).$queryURL.'"> Последняя &raquo;&raquo; </a></li>';   
          }
        }
        return implode(' ', $plinks).implode('', $links).implode(' ', $slinks);
      }
      return;
    }
  }
  
  
$pagination = new pagination;
$productPages = $pagination->generate($ar);
$total = count( $ar );  
$limit = 3; 
$totalPages = ceil( $total/ $limit );
$page = max($page, 1); 
$page = min($page, $totalPages); 
$offset = ($page - 1) * $limit;
if( $offset < 0 ) $offset = 0;
$ar = array_slice( $ar, $offset, $limit );

	?><div class="list-group mxw mrgb"><?

	foreach($ar AS $key=>$data){
		
  ?><a href="<? if($logged_in==0){?>javascript:;<?}else{?>/index.php?edit=<? echo $data['id']; }?>" class="list-group-item list-group-item-action flex-column align-items-start">
    <div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1"><? echo $data['username']; ?>
	<small class="text-muted"><? echo $data['email']; ?></small>
	  </h5>
    </div>
    <p class="mb-1"><? echo $data['text']; ?></p>
    <? if($data['status']==0){?><small class="jobundone">Не выполнено<? } else { ?><small class="jobdone">Выполнено<? } ?></small><? if($data['editedbyadm']==1){?> <small class="text-muted editedbyadm">Отредактировано администратором</small><?}?>
    </a><?
		
	}

?></div>
<nav class="mxw">
<?
echo $pageNumbers = '<ul class="pagination">'.$pagination->links().'</ul>';
?>
</nav>
</div>
</body>
</html><?

} 

?>