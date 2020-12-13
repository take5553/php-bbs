<?php
 $dsn = 'mysql:dbname=bbs;host=localhost';
 $user = 'bbs';
 $password = 'bbshoge';

 print('MySQLへの接続テスト'.'<br />');
 try {
     $dbh = new PDO($dsn, $user, $password);
     $sql = 'select * from posts';
     foreach ($dbh->query($sql) as $row) {
         print($row['id'].'->');
         print($row['name'].', ');
         print($row['email'].', ');
         print($row['body'].', ');
         print($row['posted_at']);
         print('<br />');
     }
 } catch (PDOException $e) {
     print('Error:'.$e->getMessage());
     die();
 }
$dbh = null;
