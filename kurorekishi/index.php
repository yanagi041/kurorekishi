<?php
//これはgithub用に変更したコメント。テストブランチ用

ini_set('log_errors', 'on');
ini_set('error_log', 'php.log');
session_start();

define("MY_HP", 500);
// 黒歴史達格納用
$rekishi = array();

class Rekishi{
    //プロパティ
    private $name;
    private $hp;
    private $img;
    private $attack = ''; //nullを入れたくないので空文字
    //コンストラクタ
    public function __construct($name, $hp, $img, $attack, $special){
        $this->name = $name;
        $this->hp = $hp;
        $this->img = $img;
        $this->attack = $attack;
        $this->special = $special;
    }
    //メソッド
    // public function attack(){
    //     
    // }
    public function attack(){
      $attackPoint = $this->attack;
      if(!mt_rand(0,9)){
      $attackPoint *= 1.5;
      $attackPoint = (int)$attackPoint;
      $_SESSION['history'] .= $this->getName().'のクリティカルヒット!!嫌な記憶が蘇る!!';
    }
    $_SESSION['myhp'] -= $this->attack;
    $_SESSION['history'] .= $this->attack.'ポイントの精神的ダメージ！<br>';
  }
    //セッター
    public function setHp($num){
        $this->hp = filter_var($num, FILTER_VALIDATE_INT);
    }
    // public function setAttack($num){
    // $this->attack = (int)filter_var($num, FILTER_VALIDATE_FLOAT);
    // }

    //ゲッター
    public function getName(){
        return $this->name;
    }
    public function getHp(){
        return $this->hp;
    }
    public function getImg(){
        return $this->img;
    }
    public function getAttack(){
        return $this->attack;
    }
    public function getSepcial(){
      return $this->special;
  }
}
//魔法を覚えるモンスタークラス
class MagickRekishi extends Rekishi{
  private $magicAttack;
  function __construct($name, $hp, $img, $attack, $specialAttack, $magicAttack){
    parent::__construct($name, $hp, $img, $attack, $specialAttack);
    $this->magicAttack = $magicAttack;
  }
  public function getMagicAttack(){
    return $this->magicAttack;
  }
  public function magicAttack(){
    $_SESSION['history'] .= $tihs->name.'の精神攻撃!!<br>';
    $_SESSION['myhp'] -= $this->magicAttack;
    $_SESSION['history'] .= $this->magicAttack.'ポイントの精神ダメージを受けた!<br>';
  }
}
//インスタンス作成
$rekishi[] = new rekishi('ミギテガ・ウズク', 100,'img/rekishi01.png', mt_rand(20,30), mt_rand(20,40) );
$rekishi[] = new rekishi('ミギテガ・ウズクノ', 100,'img/rekishi02.png', mt_rand(20,30), mt_rand(20,40) );
$rekishi[] = new rekishi('キョウソ・サマ', 300,'img/rekishi03.png', mt_rand(34,44), mt_rand(20,40) );
$rekishi[] = new rekishi('あれはきっと夢だったんだ', 10,'img/rekishi04.png', mt_rand(1,3), mt_rand(2,4) );
$rekishi[] = new rekishi('レイカン・アルンダ', 333,'img/rekishi05.png', 44, 44 );
$rekishi[] = new rekishi('ソシキニ・オワレテル', 200,'img/rekishi06.png', mt_rand(20,30), mt_rand(20,50) );
$rekishi[] = new rekishi('チンカラ・ホイ', 10,'img/rekishi07.png', mt_rand(1,3), mt_rand(2,4) );
$rekishi[] = new rekishi('スーパー・ハカー', 444,'img/rekishi08.png', 44, 999 );
$rekishi[] = new rekishi('ジゴクノ・ゴウカ', 200,'img/rekishi01.png', mt_rand(20,30), mt_rand(50,100) );
$rekishi[] = new rekishi('・・・', 100,'img/rekishi10.png', 0, 0 );
$rekishi[] = new rekishi('シンパイ・サセヤガッテ', 400,'img/rekishi11.png', mt_rand(30,120), mt_rand(200,250) );
$rekishi[] = new rekishi('フフフ', 100,'img/rekishi12.png', mt_rand(10,30), mt_rand(20,40) );
$rekishi[] = new rekishi('へへへ', 100,'img/rekishi13.png', mt_rand(10,30), mt_rand(20,40) );
$rekishi[] = new rekishi('イウコト・キケナイノ？', 300,'img/rekishi14.png', mt_rand(10,20), mt_rand(20,100) );
$rekishi[] = new rekishi('ラ・ブライバー', 250,'img/rekishi15.png', 25, 250 );
$rekishi[] = new rekishi('ク・トゥルフ', 444,'img/rekishi16.png', 22, mt_rand(200,400) );
$rekishi[] = new rekishi('オマエヲ・マモル', 1,'img/rekishi17.png', 999, 999 );
$rekishi[] = new rekishi('カタカタカタ・ターンッ', 300,'img/rekishi18.png', mt_rand(50,80), mt_rand(100,240) );
$rekishi[] = new rekishi('ヤレヤレ・セカイヲ・スクイマスカ', 300,'img/rekishi19.png', mt_rand(100,120), mt_rand(200,240) );
$rekishi[] = new rekishi('ヒキダシニ・ノートガ・アッタワヨ', 500,'img/rekishi20.png', 999, 999 );

function createRekishi(){
    global $rekishi;
    $rekishi = $rekishi[mt_rand(0,19)];
    $_SESSION['history'] .= '†'.$rekishi->getName().'†'.'があらわれた！！<br>';
    $_SESSION['rekishi'] = $rekishi;
}
function init(){
    $_SESSION['history'] .= '殲滅・開始<br>';
    $_SESSION['knockDownCount'] = 0;
    $_SESSION['myhp'] = MY_HP;
    createRekishi();
}
function gameOver(){
    $_SESSION = array();
}

//1.POST送信されていた場合
if(!empty($_POST)){
    $attackFlg = (!empty($_POST['attack'])) ? true : false;
    $startFlg = (!empty($_POST['start'])) ? true : false;
    error_log('POSTされた！');

    if($startFlg){
        $_SESSION['history'] = 'ゲームスタート！<br>';
        init();
    }else{
        //攻撃するを押した場合
        if($attackFlg){
            $_SESSION['history'] .= 'うわぁぁぁぁぁぁぁ！<br>';

            //ランダムで黒歴史に攻撃を与える
            $attackPoint = mt_rand(50,200);
            $_SESSION['rekishi']->setHp( $_SESSION['rekishi']->getHp() - $attackPoint);
            $_SESSION['history'] .= $attackPoint.'ポイントのダメージを与えた！<br>';
            
            //黒歴史から攻撃を受ける
            $_SESSION['rekishi']->attack();
            if($_SESSION['rekishi'] instanceof MagicRekishi){
              if(!mt_rand(0,4)){
                $_SESSION['rekishi']->magicAttack();
              }else{
                $_SESSION['rekishi']->attack();
              }
            }else{
              $_SESSION['rekishi']->attack();
            }
            // if(!mt_rand(0,9)){//10分の１の確率でクリティカルを受ける
            // $_SESSION['rekishi']->setAttack($_SESSION['rekishi']->getAttack()*1.5);
            // $_SESSION['history'] .= $_SESSION['rekishi']->getName().'の心をえぐる攻撃！！<br>';
            // }
            // $_SESSION['rekishi']->attack();	

            //HPが0以下でゲームオーバー
            if($_SESSION['myhp'] <= 0){
                gameOver();
            }else{
                //HPが０以下で次の黒歴史を表示させる
                if($_SESSION['rekishi']->getHp() <= 0){
                    $_SESSION['history'] .= $_SESSION['rekishi']->getName().'を倒して記憶から抹消した！<br>';
                    createRekishi();
                    $_SESSION['knockDownCount'] = $_SESSION['knockDownCount']+1;
                }
            }  
        }else{//逃げるを押した場合
            $_SESSION['history'] .= 'そんなの知らない！逃げ出した！<br>';
            createRekishi();
        }
    }
    $_POST = array();
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>黒歴史クエスト</title>
    <style>
    	body{
	    	margin: 0 auto;
	    	padding: 150px;
	    	width: 25%;
	    	background: #fbfbfa;
        color: white;
    	}
    	h1{ color: white; font-size: 20px; text-align: center;}
      h2{ color: white; font-size: 16px; text-align: center;}
    	form{
	    	overflow: hidden;
    	}
    	input[type="text"]{
    		color: #545454;
	    	height: 60px;
	    	width: 100%;
	    	padding: 5px 10px;
	    	font-size: 16px;
	    	display: block;
	    	margin-bottom: 10px;
	    	box-sizing: border-box;
    	}
      input[type="password"]{
    		color: #545454;
	    	height: 60px;
	    	width: 100%;
	    	padding: 5px 10px;
	    	font-size: 16px;
	    	display: block;
	    	margin-bottom: 10px;
	    	box-sizing: border-box;
    	}
    	input[type="submit"]{
	    	border: none;
	    	padding: 15px 30px;
	    	margin-bottom: 15px;
	    	background: black;
	    	color: white;
	    	float: right;
    	}
    	input[type="submit"]:hover{
	    	background: #3d3938;
	    	cursor: pointer;
    	}
    	a{
	    	color: #545454;
	    	display: block;
    	}
    	a:hover{
	    	text-decoration: none;
        
    	}
      
    </style>
  </head>
  <body>
  <h1 style="text-align:center; color:#333;">ゲーム「黒歴史を抹消せよ!!」</h1>
    <div style="background:black; padding:15px; position:relative;">
      <?php if(empty($_SESSION)){ ?>
        <h2 style="margin-top:60px;">GAME START ?</h2>
        <form method="post">
          <input type="submit" name="start" value="▶ゲームスタート">
        </form>
      <?php }else{ ?>
        <h2><?php echo '†'.$_SESSION['rekishi']->getName().'†<br>'.'が出てきた!!'; ?></h2>
        <div style="height: 150px;">
          <img src="<?php echo $_SESSION['rekishi']->getImg(); ?>" style="width:120px; height:auto; margin:40px auto 0 auto; display:block;">
        </div>
        <p style="font-size:14px; text-align:center;">黒歴史のHP：<?php echo $_SESSION['rekishi']->getHp(); ?></p>
        <p>残りの精神力：<?php echo $_SESSION['myhp']; ?></p>
        <p>抹消した黒歴史：<?php echo $_SESSION['knockDownCount']; ?></p>
        <form method="post">
          <input type="submit" name="escape" value="▶逃げる">
          <input type="submit" name="attack" value="▶攻撃する">
          <input type="submit" name="start" value="▶ゲームリスタート">
        </form>
      <?php } ?>
      <div style="position:absolute; right:-250px; top:0; color:black; width: 300px;">
        <p><?php echo (!empty($_SESSION['history'])) ? $_SESSION['history'] : ''; ?></p>
      </div>
    </div>
    
  </body>
</html>
