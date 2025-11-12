<?php
require_once __DIR__ . '/../Config/conection.php';

function initialize() {
    global $conn;

    // Só cria a lista uma vez por sessão
    if (!isset($_SESSION['codes'])) {
        $codes = [];

        /* ---- codbaixar ---- */
        $sql = "SELECT cb.ID,
                       'download'      AS type,
                       c.Nome          AS Categoria,
                       l1.Nome         AS LinguagemI,
                       l2.Nome         AS LinguagemII,
                       cb.Link         AS content,
                       cb.caminho
                FROM codbaixar cb
                JOIN categorias   c  ON cb.Categoria   = c.ID
                JOIN linguagens   l1 ON cb.LinguagemI  = l1.ID
                JOIN linguagens   l2 ON cb.LinguagemII = l2.ID";
        $res = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($res)) {
            $codes[] = $row;
        }

        /* ---- codcopiar ---- */
        $sql = "SELECT cc.ID,
                       'copy'          AS type,
                       c.Nome          AS Categoria,
                       l1.Nome         AS LinguagemI,
                       l2.Nome         AS LinguagemII,
                       cc.Codigo       AS content,
                       cc.caminho
                FROM codcopiar cc
                JOIN categorias   c  ON cc.Categoria   = c.ID
                JOIN linguagens   l1 ON cc.LinguagemI  = l1.ID
                JOIN linguagens   l2 ON cc.LinguagemII = l2.ID";
        $res = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($res)) {
            $codes[] = $row;
        }

        shuffle($codes);
        $_SESSION['codes'] = $codes;
    }

    // Quantidade inicial de cards (máx. 2)
    if (!isset($_SESSION['loaded'])) {
        $_SESSION['loaded'] = min(2, count($_SESSION['codes']));
    }
}

function loadMore() {
    $total      = count($_SESSION['codes']);
    $remaining  = $total - $_SESSION['loaded'];

    if ($remaining > 0) {
        $_SESSION['loaded'] += min(6, $remaining);
    }
}

function displayCards() {
    $slice = array_slice($_SESSION['codes'], 0, $_SESSION['loaded']);

    foreach ($slice as $row) {
        ?>
        <div class="componente">
            <img class="componente-imagem"
                 src="<?= htmlspecialchars($row['caminho']) ?>"
                 alt="preview">

            <div class="componente-info">
                <div class="componente-tipos">
                    <p class="componente-tipo"><?= htmlspecialchars($row['Categoria']) ?></p>
                    <p class="componente-tipo"><?= htmlspecialchars($row['LinguagemI']) ?></p>
                    <p class="componente-tipo"><?= htmlspecialchars($row['LinguagemII']) ?></p>
                </div>

                <div class="componente-opcoes">
                    <i class="bi bi-copy"></i>
                    <button class="componente-btn"></button>
                </div>
            </div>
        </div>
        <?php
    }
}
?>