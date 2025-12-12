<?php 
    require_once '../../Config/conection.php';
    session_start();

    if ($_SESSION['email'] == 'regressor@gmail.com' || $_SESSION['email'] == 'srpacheco@gmail.com'|| $_SESSION['email'] == 'picanha@gmail.com') {
        # code...
    } else {
        header('Location: /?login=erro');
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> CodePiece - Soluções Incríveis para Você</title>
    <meta name="description" content="">
    <meta name="robots" content="index, follow">
    <meta name="keywords" content="">
    <link rel="canonical" href="https://www.meusite.com.br">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="menu">
                <nav>
                    <a href="#usuarios">Usuários</a>
                    <a href="#baiusuario">BaiUsuario</a>
                    <a href="#copusuario">CopUsuario</a>
                    <a href="#linguagens">Linguagens</a>
                    <a href="#categorias">Categorias</a>
                    <a href="#perguntas">Perguntas</a>
                    <a href="https://freecodex.com.br" target="_blank" rel="noopener">Site</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- ====================== USUÁRIOS ====================== -->
    <section id="usuarios">
        <div class="container1">
            <h2>Usuários Cadastrados</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Nascimento</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT ID, Imagem, Nome, Email, Telefone, DataNasc, Status FROM usuarios ORDER BY ID";
                        $result = mysqli_query($conn, $query);
                        if ($result && mysqli_num_rows($result) > 0):
                            while ($user = mysqli_fetch_assoc($result)):
                        ?>
                            <tr>
                                <td><strong>#<?= $user['ID'] ?></strong></td>
                                <td><img src="<?= htmlspecialchars($user['Imagem']) ?>" alt="Foto do usuário" class="user-avatar"></td>
                                <td><strong><?= htmlspecialchars($user['Nome']) ?></strong></td>
                                <td><?= htmlspecialchars($user['Email']) ?></td>
                                <td><?= htmlspecialchars($user['Telefone']) ?></td>
                                <td><?= date('d/m/Y', strtotime($user['DataNasc'])) ?></td>
                                <td>
                                    <span class="status-badge <?= $user['Status'] === 'a' ? 'ativo' : 'desativo' ?>">
                                        <?= $user['Status'] === 'a' ? 'Ativo' : 'Desativo' ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn-editar"
                                            data-id="<?= $user['ID'] ?>"
                                            data-nome="<?= htmlspecialchars($user['Nome']) ?>"
                                            data-email="<?= htmlspecialchars($user['Email']) ?>"
                                            data-telefone="<?= htmlspecialchars($user['Telefone']) ?>"
                                            data-nascimento="<?= $user['DataNasc'] ?>"
                                            data-imagem="<?= htmlspecialchars($user['Imagem']) ?>"
                                            data-status="<?= $user['Status'] ?>">
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; else: ?>
                            <tr><td colspan="8" class="empty-msg">Nenhum usuário encontrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ====================== MODAL DE EDIÇÃO ====================== -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                Editar Usuário #<span id="modal-id-display"></span>
            </div>
            <div class="modal-body">
                <form id="formEditarUsuario">
                    <input type="hidden" name="acao" value="editar_usuario">
                    <input type="hidden" name="id" id="edit-id">

                    <!-- Campo: Nome -->
                    <div class="form-group">
                        <input type="text" name="nome" id="edit-nome" placeholder=" " required>
                        <label for="edit-nome">Nome completo</label>
                    </div>

                    <!-- Campo: Email -->
                    <div class="form-group">
                        <input type="email" name="email" id="edit-email" placeholder=" " required>
                        <label for="edit-email">Email</label>
                    </div>

                    <!-- Campo: Telefone -->
                    <div class="form-group">
                        <input type="text" name="telefone" id="edit-telefone" placeholder=" " required>
                        <label for="edit-telefone">Telefone</label>
                    </div>

                    <!-- Campo: Nascimento -->
                    <div class="form-group">
                        <input type="date" name="nascimento" id="edit-nascimento" required>
                        <label for="edit-nascimento">Data de Nascimento</label>
                    </div>

                    <!-- Campo: Imagem (com preview ao lado) -->
                    <div class="form-group image-group">
                        <input type="text" name="imagem" id="edit-imagem" placeholder=" " required>
                        <label for="edit-imagem" style="margin-top: 10px;">URL da Foto (Steam, GitHub...)</label>
                        <img id="preview-imagem" src="" alt="Preview" class="preview-thumb">
                    </div>

                    <!-- Campo: Status -->
                    <div class="form-group">
                        <select name="status" id="edit-status">
                            <option value="a">Ativo</option>
                            <option value="d">Desativo</option>
                        </select>
                        <label for="edit-status">Status do usuário</label>
                    </div>

                    <div class="modal-actions">
                        <button type="button" id="btn-cancelar">Cancelar</button>
                        <button type="submit">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ====================== BAIUSUARIO (Códigos para Baixar) ====================== -->
    <section id="baiusuario">
        <div class="container1">
            <h2>Códigos Salvos para Baixar (BaiUsuario)</h2>
            
            <?php
            $query = "
                SELECT 
                    u.ID as UsuarioID,
                    u.Nome,
                    u.Email,
                    bu.CodigoSalvo,
                    cb.caminho as Preview,
                    cat.Nome as Categoria,
                    l1.Nome as Lingua1,
                    l2.Nome as Lingua2
                FROM baiusuario bu
                JOIN usuarios u ON bu.UsuarioID = u.ID
                JOIN codbaixar cb ON bu.CodigoSalvo = cb.ID
                JOIN categorias cat ON cb.Categoria = cat.ID
                JOIN linguagens l1 ON cb.LinguagemI = l1.ID
                LEFT JOIN linguagens l2 ON cb.LinguagemII = l2.ID
                ORDER BY u.Nome, bu.CodigoSalvo
            ";
            $result = mysqli_query($conn, $query);
            ?>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID User</th>
                            <th>Nome do Usuário</th>
                            <th>Email</th>
                            <th>Código ID</th>
                            <th>Categoria</th>
                            <th>Linguagens</th>
                            <th>Preview</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['UsuarioID']) ?></td>
                                    <td><strong><?= htmlspecialchars($row['Nome']) ?></strong></td>
                                    <td><?= htmlspecialchars($row['Email']) ?></td>
                                    <td><span style="color:#FF2500; font-weight:600;">#<?= $row['CodigoSalvo'] ?></span></td>
                                    <td><?= htmlspecialchars($row['Categoria']) ?></td>
                                    <td><?= htmlspecialchars($row['Lingua1']) ?> <?= $row['Lingua2'] ? '+' . htmlspecialchars($row['Lingua2']) : '' ?></td>
                                    <td>
                                        <img src="<?= htmlspecialchars($row['Preview']) ?>" 
                                            alt="Preview" 
                                            style="width:100px; height:60px; object-fit:cover; border-radius:8px; border:2px solid #ff2500;">
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align:center; color:#aaa; font-style:italic;">
                                    Nenhum código para baixar foi salvo ainda.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>


    <!-- ====================== COPUSUARIO (Códigos para Copiar) ====================== -->
    <section id="copusuario">
        <div class="container1">
            <h2>Códigos Salvos para Copiar (CopUsuario)</h2>
            
            <?php
            $query = "
                SELECT 
                    u.ID as UsuarioID,
                    u.Nome,
                    u.Email,
                    cu.CodigoSalvo,
                    cc.caminho as Preview,
                    cat.Nome as Categoria,
                    l1.Nome as Lingua1,
                    l2.Nome as Lingua2
                FROM copusuario cu
                JOIN usuarios u ON cu.UsuarioID = u.ID
                JOIN codcopiar cc ON cu.CodigoSalvo = cc.ID
                JOIN categorias cat ON cc.Categoria = cat.ID
                JOIN linguagens l1 ON cc.LinguagemI = l1.ID
                LEFT JOIN linguagens l2 ON cc.LinguagemII = l2.ID
                ORDER BY u.Nome, cu.CodigoSalvo
            ";
            $result = mysqli_query($conn, $query);
            ?>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID User</th>
                            <th>Nome do Usuário</th>
                            <th>Email</th>
                            <th>Código ID</th>
                            <th>Categoria</th>
                            <th>Linguagens</th>
                            <th>Preview</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['UsuarioID']) ?></td>
                                    <td><strong><?= htmlspecialchars($row['Nome']) ?></strong></td>
                                    <td><?= htmlspecialchars($row['Email']) ?></td>
                                    <td><span style="color:#0066FF; font-weight:600;">#<?= $row['CodigoSalvo'] ?></span></td>
                                    <td><?= htmlspecialchars($row['Categoria']) ?></td>
                                    <td><?= htmlspecialchars($row['Lingua1']) ?> <?= $row['Lingua2'] ? '+' . htmlspecialchars($row['Lingua2']) : '' ?></td>
                                    <td>
                                        <img src="<?= htmlspecialchars($row['Preview']) ?>" 
                                            alt="Preview" 
                                            style="width:100px; height:60px; object-fit:cover; border-radius:8px; border:2px solid #0066FF;">
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align:center; color:#aaa; font-style:italic;">
                                    Nenhum código para copiar foi salvo ainda.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section id="linguagens">
        <div class="container1">
            <h2>Linguagens</h2>
            <div class="table-container">
              <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = "SELECT * FROM linguagens";
                        $result = mysqli_query($conn, $query);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Nome']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2'>Nenhum registro encontrado.</td></tr>";
                        }
                        mysqli_free_result($result);
                    ?>
                </tbody>
              </table>
            </div>
            <h3>Cadastrar Nova Linguagem</h3>
            <!-- Cadastrar Linguagem -->
            <form id="form-linguagem">
                <input type="hidden" name="acao" value="cadastrar_linguagem">
                <label>Nome da Linguagem:</label>
                <input type="text" name="nome" required>
                <button type="submit">Cadastrar</button>
            </form>
        </div>
    </section>

    <section id="categorias">
        <div class="container1">
            <h2>Categorias</h2>
            <div class="table-container">
              <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = "SELECT * FROM categorias";
                        $result = mysqli_query($conn, $query);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Nome']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2'>Nenhum registro encontrado.</td></tr>";
                        }
                        mysqli_free_result($result);
                    ?>
                </tbody>
              </table>
            </div>
            <h3>Cadastrar Nova Categoria</h3>
            <!-- Cadastrar Categoria -->
            <form id="form-categoria">
                <input type="hidden" name="acao" value="cadastrar_categoria">
                <label>Nome da Categoria:</label>
                <input type="text" name="nome" required>
                <button type="submit">Cadastrar</button>
            </form>
        </div>
    </section>

    <section id="perguntas">
        <div class="container1">
            <h2>Perguntas</h2>
            <div class="table-container">
              <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pergunta</th>
                        <th>Resposta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = "SELECT * FROM perguntas";
                        $result = mysqli_query($conn, $query);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Pergunta']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Resposta']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>Nenhum registro encontrado.</td></tr>";
                        }
                        mysqli_free_result($result);
                    ?>
                </tbody>
              </table>
            </div>
            <h3>Cadastrar Nova Pergunta</h3>
            <!-- Cadastrar Pergunta -->
            <form id="form-pergunta">
                <input type="hidden" name="acao" value="cadastrar_pergunta">
                <label>Pergunta:</label>
                <input type="text" name="pergunta" required>
                <label>Resposta:</label>
                <textarea name="resposta" required></textarea>
                <button type="submit">Cadastrar</button>
            </form>
        </div>

        <!-- BOTÃO DE DESLOGAR ESTILIZADO E FIXO -->
        <a href="/Src/Utils/logoff.php" class="btn-logout">
            <i class="bi bi-box-arrow-right"></i>
            Sair do Painel
        </a>
    </section>

    <?php mysqli_close($conn); ?>

    <script>
        // Seu JavaScript permanece exatamente o mesmo (só atualizei o preview para usar classes)
        document.querySelectorAll('.btn-editar').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-id').value = this.dataset.id;
                document.getElementById('modal-id-display').textContent = this.dataset.id;
                document.getElementById('edit-nome').value = this.dataset.nome;
                document.getElementById('edit-email').value = this.dataset.email;
                document.getElementById('edit-telefone').value = this.dataset.telefone;
                document.getElementById('edit-nascimento').value = this.dataset.nascimento;
                document.getElementById('edit-imagem').value = this.dataset.imagem;
                document.getElementById('preview-imagem').src = this.dataset.imagem || 'https://via.placeholder.com/52';

                // === SOLUÇÃO PERFEITA ===
                const selectStatus = document.getElementById('edit-status');
                const statusAtual = this.dataset.status;
                selectStatus.value = (statusAtual === 'i' || statusAtual === 'd') ? 'd' : 'a';

                // Força label subir
                const label = selectStatus.nextElementSibling;
                label.style.top = '0';
                label.style.fontSize = '12px';
                label.style.color = '#FF2500';
                label.style.fontWeight = '700';

                document.getElementById('modalEditar').classList.add('active');
            });
        });

        document.getElementById('btn-cancelar').addEventListener('click', () => {
            document.getElementById('modalEditar').classList.remove('active');
        });

        document.getElementById('edit-imagem').addEventListener('input', function() {
            const url = this.value.trim();
            document.getElementById('preview-imagem').src = url || 'https://via.placeholder.com/52';
        });

        // Seu código de submit permanece igual
        document.getElementById('formEditarUsuario').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('acao', 'editar_usuario');

            const response = await fetch('https://FreeCodex.com.br/Src/Services/apiAdimin.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            alert(result.message);
            if (response.ok) {
                document.getElementById('modalEditar').classList.remove('active');
                location.reload();
            }
        });

        // Cadastros normais (linguagem, categoria, pergunta)
        document.querySelectorAll('#form-linguagem, #form-categoria, #form-pergunta').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const data = new FormData(form);
                const res = await fetch('https://FreeCodex.com.br/Src/Services/apiAdimin.php', { method: 'POST', body: data });
                const json = await res.json();
                alert(json.message);
                if (res.ok) { form.reset(); location.reload(); }
            });
        });
    </script>
</body>
</html>