<?php
// Começar a sessão
session_start();

// Se não está logado, vai para o login
if (!isset($_SESSION['usuario'])) { 
    header('Location: login.php'); 
    exit(); 
}

// Conectar no banco
include 'config.php';
include 'funcoes_estoque.php';

// Verificar se tem estoque baixo
$alerta_estoque = gerarAlertaEstoque($conn);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Studio D.I.Y</title>

    <style>
/* ======== RESET E BASE ======== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background: linear-gradient(135deg, #ffe6f0, #fff0f6);
  color: #333;
  min-height: 100vh;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding: 30px 15px;
}

/* ======== CONTAINER ======== */
.container {
  background: #fff;
  width: 100%;
  max-width: 900px;
  border-radius: 20px;
  box-shadow: 0 8px 25px rgba(214, 51, 108, 0.25);
  padding: 40px 30px;
  animation: fadeIn 0.8s ease-in-out;
}

/* ======== TÍTULO E BOAS-VINDAS ======== */
h1 {
  text-align: center;
  color: #d6336c;
  font-size: 2rem;
  margin-bottom: 15px;
  font-weight: 700;
}

.welcome {
  text-align: center;
  font-size: 1.1em;
  color: #555;
  margin-bottom: 25px;
}

/* ======== ALERTA DE ESTOQUE ======== */
.alert {
  background-color: #fff0f6;
  border: 2px solid #ff80ab;
  border-radius: 15px;
  padding: 20px;
  margin-bottom: 30px;
  animation: pulse 1.5s infinite alternate;
}

.alert h3 {
  color: #d6336c;
  margin-top: 0;
  margin-bottom: 10px;
  font-size: 1.2rem;
}

.alert ul {
  padding-left: 25px;
  margin-bottom: 10px;
}

.alert a {
  display: inline-block;
  background: linear-gradient(135deg, #d6336c, #f0569b);
  color: #fff;
  text-decoration: none;
  padding: 10px 18px;
  border-radius: 10px;
  transition: 0.3s;
  font-weight: 500;
}

.alert a:hover {
  background: linear-gradient(135deg, #b81e53, #fc4999);
  transform: scale(1.05);
  box-shadow: 0 4px 10px rgba(214, 51, 108, 0.4);
}

/* ======== MENU ======== */
.menu-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
}

.menu-item {
  background-color: #ffe6f0;
  border: 2px solid #f9bcd0;
  border-radius: 15px;
  padding: 25px 15px;
  text-align: center;
  text-decoration: none;
  color: #333;
  transition: all 0.3s ease;
}

.menu-item:hover {
  background-color: #ffd1e0;
  transform: translateY(-5px);
  box-shadow: 0 6px 15px rgba(214, 51, 108, 0.25);
}

.menu-item h3 {
  color: #d6336c;
  margin-bottom: 10px;
  font-size: 1.2rem;
}

.menu-item p {
  color: #555;
  font-size: 0.95em;
}

/* ======== BOTÃO SAIR ======== */
.btn {
  background: linear-gradient(135deg, #d6336c, #f0569b);
  color: #fff;
  text-decoration: none;
  padding: 12px 25px;
  border-radius: 25px;
  font-weight: 600;
  display: inline-block;
  transition: 0.3s;
  text-align: center;
}

.btn:hover {
  background: linear-gradient(135deg, #b81e53, #fc4999);
  transform: scale(1.05);
  box-shadow: 0 4px 10px rgba(214, 51, 108, 0.4);
}

/* Centralização do botão */
.container > div:last-child {
  text-align: center;
  margin-top: 35px;
}

/* ======== ANIMAÇÕES ======== */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes pulse {
  from {
    box-shadow: 0 0 5px rgba(214, 51, 108, 0.2);
  }
  to {
    box-shadow: 0 0 20px rgba(214, 51, 108, 0.4);
  }
}

/* ======== RESPONSIVIDADE ======== */
@media (max-width: 768px) {
  .container {
    padding: 25px 20px;
  }

  h1 {
    font-size: 1.6rem;
  }

  .menu-item {
    padding: 18px;
  }

  .btn {
    width: 100%;
    border-radius: 15px;
  }
}

@media (max-width: 480px) {
  .menu-grid {
    grid-template-columns: 1fr;
  }

  .alert {
    padding: 15px;
  }

  .alert a {
    width: 100%;
    text-align: center;
  }
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Sistema Studio D.I.Y</h1>        
        <div class="welcome">
            Bem-vindo, <?= htmlspecialchars($_SESSION['usuario']['nome']) ?>!
        </div>

        <?php if ($alerta_estoque): ?>
            <div class="alert">
                <h3>Estoque Baixo!</h3>
                <ul>
                    <?php foreach ($alerta_estoque['esmaltes'] as $esmalte): ?>
                        <li><?= htmlspecialchars($esmalte['nome']) ?> - <?= $esmalte['estoque_atual'] ?>/<?= $esmalte['estoque_minimo'] ?></li>
                    <?php endforeach; ?>
                </ul>
                <p><a href="movimentacoes.php">Registrar Movimentações</a></p>
            </div>
        <?php endif; ?>

        <div class="menu-grid">
            <a href="esmaltes.php" class="menu-item">
                <h3>Esmaltes</h3>
                <p>Gerenciar catálogo</p>
            </a>
            <a href="movimentacoes.php" class="menu-item">
                <h3>Movimentações</h3>
                <p>Entrada e saída</p>
            </a>
            <a href="historico.php" class="menu-item">
                <h3>Histórico</h3>
                <p>Ver movimentações</p>
            </a>
        </div>
        <div style="text-align: center; margin-top: 30px;">
            <a href="logout.php" class="btn">Sair</a>
        </div>
    </div>
</body>
</html>
