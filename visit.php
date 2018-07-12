<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<title>訪問者数のPHPスクリプト</title>
<?php
{
  //
  // 訪問者のIPごとに訪問回数をカウントする関数
  //
  function VisitCount( $ip, $visit, $file="data/visit.txt")
  {
    $counter = 0;   // 訪問回数をゼロに初期化
    $last_visit=""; // 最後に訪問した時刻(文字列)を空に初期化

    // 訪問回数を記録するファイル"data/visit.txt"がある場合
    if ( file_exists($file) ){
      $fp = fopen($file,"r");
      while( ($data=fgetcsv($fp)) !== FALSE){
        if($data[0] === $ip){
          $counter++;
          $last_visit = $data[1];
        }
      }
      fclose($fp);
    }

    // リモートホストのIPと訪問時刻を","でつなぎ，改行を加えて"1行"の文字列にする
    $line = implode(',',array($ip, $visit))."\n";
    // "data/visit.txt"の最終行に追記する
    file_put_contents($file, $line ,FILE_APPEND);
    // 今回の訪問回数を加える
    $counter++;

    // 訪問回数と最後に訪れた時刻を配列にして呼び出し元に返す
    return array($counter, $last_visit);
  }

  $remote_ip = $_SERVER['REMOTE_ADDR'];
  // 日付と時刻のフォーマットにしたがって取得
  $date_time=date("Y-m-d H:i:s");

  list($counter, $last_date) = VisitCount($remote_ip, $date_time);

  // 初めて訪問した人にごあいさつ
  if($counter === 1){
    $message='はじめてご訪問いただき，ありがとうございます！！';
  }
  // 10回以上訪問した人に感謝のメッセージ
  if($counter >= 10){
    $message='いつもありがとうございます！！';
  }
}
?>
<body>
<?php
// 以下のHTMLで関数の機能をテストする
echo '<h1>ようこそ</h1>';
echo '<h2>'.$message.'</h2>';
// 2回以上訪問した人に追加のメッセージを送る
if($counter>1){
  echo '<h2>あなたは'.$counter.'回目の訪問です！</h2>';
  echo '<h2>前回は'.$last_date.'に訪問しました</h2>';
}
?>
</body>
</html>
