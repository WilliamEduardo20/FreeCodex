<?php
require_once __DIR__ . '/../Config/conection.php';

/** Inicializa favoritos do usuário (4 na primeira vez) */
function initFavorites(): void {
    if (isset($_SESSION['favorites'])) {
        setInitialFavoritesLoad();
        return;
    }

    $userId = $_SESSION['id'] ?? null;
    if (!$userId) return;

    $favorites = [];

    // codcopiar salvos
    $sql = "SELECT cc.ID, 'copy' AS type, c.Nome AS Categoria,
                   l1.Nome AS LinguagemI, l2.Nome AS LinguagemII,
                   cc.Codigo AS content, cc.caminho
            FROM copusuario cu
            JOIN codcopiar cc ON cu.CodigoSalvo = cc.ID
            JOIN categorias c  ON cc.Categoria   = c.ID
            JOIN linguagens l1 ON cc.LinguagemI  = l1.ID
            JOIN linguagens l2 ON cc.LinguagemII = l2.ID
            WHERE cu.UsuarioID = ?";
    addFavoriteResults($sql, $userId, $favorites);

    // codbaixar salvos
    $sql = "SELECT cb.ID, 'download' AS type, c.Nome AS Categoria,
                   l1.Nome AS LinguagemI, l2.Nome AS LinguagemII,
                   cb.Link AS content, cb.caminho
            FROM baiusuario bu
            JOIN codbaixar cb ON bu.CodigoSalvo = cb.ID
            JOIN categorias c  ON cb.Categoria   = c.ID
            JOIN linguagens l1 ON cb.LinguagemI  = l1.ID
            JOIN linguagens l2 ON cb.LinguagemII = l2.ID
            WHERE bu.UsuarioID = ?";
    addFavoriteResults($sql, $userId, $favorites);

    $_SESSION['favorites'] = $favorites;
    setInitialFavoritesLoad();
}

/** Executa consulta e adiciona resultados */
function addFavoriteResults(string $sql, int $userId, array &$array): void {
    $stmt = mysqli_prepare($GLOBALS['conn'], $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($res)) {
        $array[] = $row;
    }
    mysqli_stmt_close($stmt);
}

/** Define carregamento inicial (4 cards) */
function setInitialFavoritesLoad(): void {
    $_SESSION['fav_loaded'] = min(4, count($_SESSION['favorites'] ?? []));
}

/** Carrega +6 cards */
function loadMoreFavorites(): void {
    $total = count($_SESSION['favorites'] ?? []);
    $remaining = $total - ($_SESSION['fav_loaded'] ?? 0);
    if ($remaining > 0) {
        $_SESSION['fav_loaded'] += min(6, $remaining);
    }
}

/** Renderiza os cards de favoritos */
function renderFavorites(): void {
    $slice = array_slice($_SESSION['favorites'] ?? [], 0, $_SESSION['fav_loaded'] ?? 0);
    $h = 'htmlspecialchars';

    foreach ($slice as $r) {
        printf(
            '<div class="componente"><img class="componente-imagem" src="%s" alt="preview"><div class="componente-info"><div class="componente-tipos"><p class="componente-tipo">%s</p><p class="componente-tipo">%s</p><p class="componente-tipo">%s</p></div><div class="componente-opcoes"><i class="bi bi-copy"></i><button class="componente-btn"></button></div></div></div>',
            $h($r['caminho']),
            $h($r['Categoria']),
            $h($r['LinguagemI']),
            $h($r['LinguagemII'])
        );
    }
}
?>