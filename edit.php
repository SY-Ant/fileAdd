<?php

require_once 'functions.php';

function postback () {

  //==========失败的可能性===============

  if (empty($_POST['name'])|| empty($_POST['gender']) ||empty($_POST['birthday'])) {
    $GLOBALS['error_str'] = '完整填写表单';
    return;
  }
  if (!(isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK)) {
    $GLOBALS['error_str'] = '上传头像失败';
    return;
  }
  $tem_path = $_FILES['avatar']['tmp_name'];
  $dest_path = './assets/img/' . $_FILES['avatar']['name'];
  $moved = move_uploaded_file($tem_path , $dest_path);

  if (!$moved) {
      $GLOBALS['error_str'] = '上传图片失败';
      return;
  }

 //==========成功之后的状态===============
  //后台获取用户数据
  $name = $_POST['name'];
  $gender = $_POST['gender'] === '-1' ? null : $_POST['gender'] === 'male' ? 0 : 1;
  $birthday = $_POST['birthday'];
  $avatar = '/user-crud' .substr($dest_path,1);
  //将数据进行相关的存放
  $conn = db_connect();

  $sql_str ="insert into begin value (null,'{$avatar}','{$name}','{$gender}','{$birthday}');";
  
  mysqli_query($conn,$sql_str);
  //检测是否移动成功,受影响行数
  $affected_rows = mysqli_affected_rows($conn);

  if ($affected_rows !== 1) {
    $GLOBALS['error_str'] = '保存失败，请重试';
    return;
  }
  header('Location: /user-crud/inde.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   postback();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>XXX管理系统</title>
  <link rel="stylesheet" href="/user-crud/assets/css/bootstrap.css">
  <link rel="stylesheet" href="/user-crud/assets/css/style.css">
</head>
<body>
  <?php include '_nav.php'; ?>
  <main class="container">
    <h1 class="heading">添加用户</h1>
    <?php if (!empty($error_str)): ?>
    <div class="alert alert-danger"><?php echo $error_str; ?></div>
    <?php endif ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="avatar">头像</label>
        <input type="file" class="form-control" id="avatar" name="avatar">
      </div>
      <div class="form-group">
        <label for="name">姓名</label>
        <input type="text" class="form-control" id="name" name="name">
      </div>
      <div class="form-group">
        <label for="gender">性别</label>
        <select class="form-control" id="gender" name="gender">
          <option value="-1">请选择性别</option>
          <option value="male">男</option>
          <option value="female">女</option>
        </select>
      </div>
      <div class="form-group">
        <label for="birthday">生日</label>
        <input type="date" class="form-control" id="birthday" name="birthday">
      </div>
      <button class="btn btn-primary">保存</button>
    </form>
  </main>
</body>
</html>
