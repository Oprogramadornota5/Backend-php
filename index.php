<?php
session_start();

// Usuários fixos só para exemplo (username => password)
$users = [
    'alice' => '123',
    'bob' => '456',
    'carol' => '789'
];

// Se usuário não logado, processa login
if (!isset($_SESSION['user'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $u = $_POST['username'] ?? '';
        $p = $_POST['password'] ?? '';
        if (isset($users[$u]) && $users[$u] === $p) {
            $_SESSION['user'] = $u;
            // Inicializa listas se não tiver
            if (!isset($_SESSION['lists'])) {
                $_SESSION['lists'] = [];
            }
            header('Location: '.$_SERVER['PHP_SELF']);
            exit;
        } else {
            $error = "Usuário ou senha inválidos.";
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="pt-BR">
    <head><meta charset="UTF-8" /><title>Login</title></head>
    <body>
        <h1>Login</h1>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post">
            <input name="username" placeholder="Usuário" required autofocus />
            <input type="password" name="password" placeholder="Senha" required />
            <button type="submit" name="login">Entrar</button>
        </form>
    </body>
    </html>

    <?php
    exit;
}

// Função para redirecionar e evitar reenvio
function redirect() {
    header('Location: '.$_SERVER['PHP_SELF']);
    exit;
}

// Inicializa listas se não tiver
if (!isset($_SESSION['lists'])) {
    $_SESSION['lists'] = [];
}

$user = $_SESSION['user'];

// POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Logout
    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    }

    // Criar lista (lista pertence ao usuário que criou, e tem array de usuários com acesso)
    if (isset($_POST['add_list'])) {
        $list_name = trim($_POST['list_name'] ?? '');
        if ($list_name !== '') {
            $_SESSION['lists'][] = [
                'name' => $list_name,
                'owner' => $user,
                'shared_with' => [], // outros usuários que têm acesso
                'tasks' => []
            ];
        }
        redirect();
    }

    // Deletar lista (só dono pode deletar)
    if (isset($_POST['remove_list'])) {
        $list_id = (int)($_POST['list_id'] ?? -1);
        if (isset($_SESSION['lists'][$list_id]) && $_SESSION['lists'][$list_id]['owner'] === $user) {
            array_splice($_SESSION['lists'], $list_id, 1);
        }
        redirect();
    }

    // Adicionar tarefa (se usuário for dono ou tiver compartilhado)
    if (isset($_POST['add_task'])) {
        $list_id = (int)($_POST['list_id'] ?? -1);
        $task_name = trim($_POST['task_name'] ?? '');
        if ($task_name !== '' && isset($_SESSION['lists'][$list_id])) {
            $list = &$_SESSION['lists'][$list_id];
            if ($list['owner'] === $user || in_array($user, $list['shared_with'])) {
                $list['tasks'][] = ['name' => $task_name, 'done' => false];
            }
        }
        redirect();
    }

    // Marcar tarefa como concluída ou não (se acesso)
    if (isset($_POST['toggle_task'])) {
        $list_id = (int)($_POST['list_id'] ?? -1);
        $task_index = (int)($_POST['task_index'] ?? -1);
        if (isset($_SESSION['lists'][$list_id]['tasks'][$task_index])) {
            $list = &$_SESSION['lists'][$list_id];
            if ($list['owner'] === $user || in_array($user, $list['shared_with'])) {
                $list['tasks'][$task_index]['done'] = !$list['tasks'][$task_index]['done'];
            }
        }
        redirect();
    }

    // Remover tarefa (se acesso)
    if (isset($_POST['remove_task'])) {
        $list_id = (int)($_POST['list_id'] ?? -1);
        $task_index = (int)($_POST['task_index'] ?? -1);
        if (isset($_SESSION['lists'][$list_id]['tasks'][$task_index])) {
            $list = &$_SESSION['lists'][$list_id];
            if ($list['owner'] === $user || in_array($user, $list['shared_with'])) {
                array_splice($list['tasks'], $task_index, 1);
            }
        }
        redirect();
    }

    // Compartilhar lista (só dono pode adicionar usuários)
    if (isset($_POST['share_list'])) {
        $list_id = (int)($_POST['list_id'] ?? -1);
        $share_user = trim($_POST['share_user'] ?? '');
        if ($share_user !== '' && isset($users[$share_user]) && isset($_SESSION['lists'][$list_id])) {
            $list = &$_SESSION['lists'][$list_id];
            if ($list['owner'] === $user && $share_user !== $user && !in_array($share_user, $list['shared_with'])) {
                $list['shared_with'][] = $share_user;
            }
        }
        redirect();
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>To-Do List com Login e Compartilhamento</title>
<style>
  body { font-family: Arial, sans-serif; margin: 2em; }
  h1 { color: #333; }
  .list { border: 1px solid #ccc; padding: 1em; margin-bottom: 1em; }
  .task-done { text-decoration: line-through; color: gray; }
  button { margin-left: 5px; }
  form { display: inline; }
  .shared { color: #007700; font-size: 0.9em; }
</style>
</head>
<body>

<h1>Olá, <?=htmlspecialchars($user)?>!</h1>

<form method="post" style="margin-bottom:1em;">
    <button name="logout" type="submit">Sair</button>
</form>

<h2>Criar nova lista</h2>
<form method="post" style="margin-bottom:2em;">
  <input type="text" name="list_name" placeholder="Nome da nova lista" required />
  <button type="submit" name="add_list">Adicionar Lista</button>
</form>

<hr>

<h2>Minhas Listas e Compartilhadas</h2>

<?php 
// Mostrar só listas que o usuário pode acessar (dono ou compartilhada)
$accessible_lists = array_filter($_SESSION['lists'], function($list) use ($user) {
    return $list['owner'] === $user || in_array($user, $list['shared_with']);
});

if (count($accessible_lists) === 0) {
    echo "<p>Você não tem listas ainda.</p>";
}

foreach ($accessible_lists as $list_id => $list): ?>
  <div class="list">
    <h3><?=htmlspecialchars($list['name'])?> 
      <?php if ($list['owner'] !== $user): ?>
        <span class="shared">(Compartilhada por <?=htmlspecialchars($list['owner'])?>)</span>
      <?php endif; ?>
    </h3>

    <?php if ($list['owner'] === $user): ?>
      <form method="post" onsubmit="return confirm('Tem certeza que quer apagar essa lista inteira?');" style="display:inline;">
        <input type="hidden" name="list_id" value="<?=$list_id?>" />
        <button type="submit" name="remove_list">Apagar Lista</button>
      </form>
    <?php endif; ?>

    <ul>
      <?php foreach ($list['tasks'] as $task_index => $task): ?>
        <li>
          <form method="post" style="display:inline;">
            <input type="hidden" name="list_id" value="<?=$list_id?>" />
            <input type="hidden" name="task_index" value="<?=$task_index?>" />
            <button type="submit" name="toggle_task"><?=$task['done'] ? "Desmarcar" : "Concluir"?></button>
          </form>
          <span class="<?=$task['done'] ? 'task-done' : ''?>"><?=htmlspecialchars($task['name'])?></span>
          <?php if ($list['owner'] === $user || in_array($user, $list['shared_with'])): ?>
            <form method="post" style="display:inline;">
              <input type="hidden" name="list_id" value="<?=$list_id?>" />
              <input type="hidden" name="task_index" value="<?=$task_index?>" />
              <button type="submit" name="remove_task">Remover</button>
            </form>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <?php if ($list['owner'] === $user || in_array($user, $list['shared_with'])): ?>
      <form method="post" style="margin-bottom: 0.5em;">
        <input type="hidden" name="list_id" value="<?=$list_id?>" />
        <input type="text" name="task_name" placeholder="Nova tarefa" required />
        <button type="submit" name="add_task">Adicionar Tarefa</button>
      </form>
    <?php endif; ?>

    <?php if ($list['owner'] === $user): ?>
      <form method="post" style="margin-top:0.5em;">
        <input type="hidden" name="list_id" value="<?=$list_id?>" />
        <input type="text" name="share_user" placeholder="Compartilhar com (usuário)" required />
        <button type="submit" name="share_list">Compartilhar Lista</button>
      </form>
    <?php endif; ?>
  </div>
<?php endforeach; ?>

</body>
</html>
