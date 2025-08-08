<?php
// index.php na raiz — despacha pro seu index real sem mover arquivos
$tentativas = [
  __DIR__ . '/index_real.php',            // caso exista um index na raiz com outro nome
  __DIR__ . '/php/index.php',
  __DIR__ . '/proj_avalia/index.php',
  __DIR__ . '/proj_avalia/php/index.php',
];

foreach ($tentativas as $alvo) {
  if (is_file($alvo)) {
    require $alvo;
    exit;
  }
}

// Se nada encontrado, mostra uma dica útil
http_response_code(404);
echo "Index não encontrado. Verifique se seu index está em php/index.php ou proj_avalia/(php/)index.php";
