<?php 
 require_once 'functions.php';

if (empty($_GET['item']) || !is_numeric($_GET['item'])) {
  exit('未提交正确的ID');
}

$id = $_GET['item'];
var_dump($id);
$conn = db_connect();

$query = mysqli_query($conn, "select * from begin where id = {$id};");

if (!$query) {
  exit('查询对应数据失败');
}

$user = mysqli_fetch_assoc($query);
var_dump($user['id']);
var_dump($user);

if (!$user) {
  exit('未找到对应数据');
}

//=======如果是表单提交请求,则意味着需要修改数据====

function postback() {
  global $user;
  $user['name'] = isset($_POST['name']) ? $_POST['name'] : '';
  $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
  $user['gender'] = $gender === '-1' ? null : $gender === 'male' ? 0 : 1;
  $user['birthday'] = isset($_POST['birthday']) ? $_POST['birthday'] : '';

  //判断是否提交图片

  if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $tem_path = $_FILES['avatar']['tmp_name'];
    $dest_path = './assets/img/' . $_FILES['avatar']['name'];
    $moved = move_uploaded_file($tem_path , $dest_path);

  if ($moved) {
    $user['avatar'] = '/user-crud' . substr($dest_path, 1); 
    }
  }

  //=======数据上传成功后==========

  $conn = db_connect();

  $sql = "update begin set name = '{$user['name']}',gender = '{$user['gender']}',birthday = '{$user['birthday']}',avatar = '{$user['avatar']}' where id = {$user['id']}";

  $query = mysqli_query($conn, $sql);

  if (!$query) {
    $GLOBALS['error_str'] = '更新失败';
    return;
  }

  if (mysqli_affected_rows($conn) !== 1) {
    $GLOBALS['error_str'] = '更新失败';
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
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?item=<?php echo $id ?>" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="avatar">头像</label>
        <input type="file" class="form-control" id="avatar" name="avatar">
        <img src="<?php echo $user['avatar']; ?>" alt="">
      </div>
      <div class="form-group">
        <label for="name">姓名</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>">
      </div>
      <div class="form-group">
        <label for="gender">性别</label>
        <select class="form-control" id="gender" name="gender">
          <option value="-1">请选择性别</option>
          <option value="male"<?php echo $user['gender'] == 0 ? ' selected' : ''; ?>>男</option>
          <option value="female"<?php echo $user['gender'] == 1 ? ' selected' : ''; ?>>女</option>
        </select>
      </div>
      <div class="form-group">
        <label for="birthday">生日</label>
        <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo $user['birthday']; ?>">
      </div>
      <button class="btn btn-primary">保存</button>
    </form>
  </main>
</body>
</html>
