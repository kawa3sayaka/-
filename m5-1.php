<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
 
    <?php 
    
    //SQL連携
    $dsn = 'データベース名';
    $user = "ユーザー名";
    $password = "パスワード";
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //以下、MｙSQLコネクト確認。success
    //if(mysqli_connect("localhost",$user,$password)){
    //echo "connect success!";
    //}else{
    //echo "connect fail!";
    //}
    
    if(isset($_POST["edit"]) && ($_POST["passEdit"])){
        $edit = $_POST["edit"];
        $passEdit = $_POST["passEdit"];
        
        $sql ="SELECT * FROM mission5 where id = '".$edit."'  AND password = '".$passEdit."'"; //*で画面表示させる編集列のデータを選択
        $stmt = $pdo->query($sql);
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
        
        $results = $stmt -> fetchAll();
        
        foreach ($results as $result){
        $renewId = $result['id'];
        $renewName = $result['name'];
        $renewComment = $result['comment'];
        }
    
        
        //編集する名前とコメントを画面上に表示するため該当するデータを一行返す

    }
    ?>   

        
    <h1>好きなアーティストと曲名を教えてね</h1>
    <form action="" method="post">
        <input type="text" name="name" placeholder="アーティスト名" value="<?php if(isset($renewName)){echo $renewName;}?>"><br>
        <input type="textarea" name="comment" placeholder="曲名" value="<?php if(isset($renewComment)){echo $renewComment;}?>"><br>
        <input type="text" name="password" placeholder="パスワード"><br>
        <p>*パスワードを打ち込まないと表示されないよ(；・∀・)</p>
        <input type="hidden" name="editNum" placeholder="編集番号" value="<?php if(isset($renewId)){echo $renewId;}?>">
        <input type="submit" name="submit" placeholder="送信"><br>
    </form>
    <br>
    <form action="" method="post">
        <input type="number" name="delete" placeholder="削除対象番号">
        <input type="text" name="passDelete" placeholder="パスワード"><br>
        <input type="submit" name="submit" value="削除">
    </form>
    <br>
    <form action="" method="post">
        <input type="number" name="edit" placeholder="編集対象番号">
        <input type="text" name="passEdit" placeholder="パスワード"><br>
        <input type="submit" name="submit" value="編集">
    </form>
    <br>
    
    <?php 
    
    //テーブルで四項目作る
    
    $stmt = $pdo-> prepare("CREATE TABLE IF NOT EXISTS mission5"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."comment VARCHAR(100),"
    ."password VARCHAR(100),"
    ."date VARCHAR(100)"
    .");");
      // (4) SQL実行
    $stmt->execute();

    
      
    //削除機能
    if(!empty($_POST["delete"]) && ($_POST["passDelete"])){
    //削除機能で入力されたパスワード
    $passDelete = $_POST["passDelete"];
    $delete = $_POST["delete"];

    $sql = "delete from mission5 where id = '".$delete."'  AND password = '".$passDelete."'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt -> execute();

        
    }elseif(!empty($_POST["name"]) && ($_POST["comment"]) && ($_POST["password"])){
        
    //入力データの受け取り
    $name=$_POST["name"];
    $comment=$_POST["comment"];
    $password = $_POST["password"];
    
    //日付データを変数に代入
    $date=date("Y/m/d/H:i:s");
    
    // editNoがないときは新規投稿、ある場合は編集 ***ここで判断
    
    if(empty($_POST["editNum"])){
    // 以下、新規投稿機能
    // INSERT文
    
    $stmt = $pdo -> prepare("INSERT INTO mission5 (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
    //値をセット
    $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
    $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
    $stmt -> bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();
    }else{
    //以下編集機能
    
    $renewId = $_POST["editNum"];
    $sql = "UPDATE mission5 SET name= :name,comment = :comment WHERE id ='".$renewId."'";
    
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name',$name,PDO::PARAM_STR);
    $stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
    $stmt->execute();
    }
    }
    
    

    //表示機能
    $sql ='SELECT * FROM mission5'; //*で全ての列のデータを選択
    $stmt = $pdo->query($sql);
    $stmt->bindParam(':id',$id,PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt -> fetchAll();
    foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
    }
    echo "<hr>";
    


    ?>
    
</body>
</html>