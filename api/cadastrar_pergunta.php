<form method="POST" action="salvar_pergunta.php">
  <?php for ($q = 1; $q <= 10; $q++): ?>
    <fieldset>
      <legend>Pergunta <?= $q ?></legend>
      <textarea name="perguntas[<?= $q ?>][enunciado]" rows="2" cols="80" required></textarea><br><br>

      <?php for ($i = 0; $i < 4; $i++): ?>
        <input type="text" name="perguntas[<?= $q ?>][alternativas][]" placeholder="Alternativa <?= chr(65 + $i) ?>" required>
        <input type="radio" name="perguntas[<?= $q ?>][correta]" value="<?= $i ?>"> Correta<br>
      <?php endfor; ?>
    </fieldset>
    <br>
  <?php endfor; ?>
  <button type="submit">Salvar todas as perguntas</button>
</form>
