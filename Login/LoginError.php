<!DOCTYPE html>
<html>
<head>
  <title>ログイン</title>
  <style>
    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .login-box {
      border: 2px solid black;
      padding: 20px;
      text-align: center;
    }
    .input-field {
      margin-bottom: 10px;
    }
    .title {
      font-size: 30px;
    }
    p{
        color:red;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="login-box">
      <h2 class="title">ログイン</h2>
      <form method="POST" action="check.php">
        <div class="input-field">
          <label for="login_id">ユーザ名:</label>
          <input type="text" id="login_id" name="login_id">
        </div>
        <div class="input-field">
          <label for="password">パスワード:</label>
          <input type="password" id="password" name="password">
        </div>
        <p>ログインに失敗しました。再度試してください。</p>
        <button type="submit">完了</button>
      </form>
    </div>
  </div>
</body>
</html>