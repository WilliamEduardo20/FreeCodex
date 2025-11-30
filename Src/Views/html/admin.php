<?php
    require_once '../../Config/conection.php'; // Inclui a conexão com o banco de dados
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
                </nav>
            </div>
        </div>
    </header>
 
    <section id="usuarios">
        <div class="container1">
            <h2>Usuários Cadastrados</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th class="no">ID</th>
                            <th class="no">Foto</th>
                            <th class="no">Nome</th>
                            <th class="no">Email</th>
                            <th class="no">Telefone</th>
                            <th class="no">Nascimento</th>
                            <th class="no">Status</th>
                            <th class="no">Ações</th>
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
                                <td>
                                    <img src="<?= htmlspecialchars($user['Imagem']) ?>" 
                                        alt="Foto" 
                                        style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:3px solid #FF2500;">
                                </td>
                                <td><strong><?= htmlspecialchars($user['Nome']) ?></strong></td>
                                <td><?= htmlspecialchars($user['Email']) ?></td>
                                <td><?= htmlspecialchars($user['Telefone']) ?></td>
                                <td><?= date('d/m/Y', strtotime($user['DataNasc'])) ?></td>
                                <td>
                                    <span style="padding:6px 12px;border-radius:20px;font-size:12px;font-weight:bold;background:<?= $user['Status']==='a'?'#4CAF50':'#F44336' ?>;color:white;">
                                        <?= $user['Status']==='a' ? 'Ativo' : 'Inativo' ?>
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
                                            data-status="<?= $user['Status'] ?>"
                                            style="background:#FF2500;color:white;border:none;padding:8px 14px;border-radius:6px;cursor:pointer;">
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; else: ?>
                            <tr><td colspan="8" style="text-align:center;color:#999;padding:40px;">Nenhum usuário encontrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ====================== MODAL DE EDIÇÃO (VERSÃO COMPACTA E LINDA) ====================== -->
    <div id="modalEditar" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.75);z-index:9999;display:flex;justify-content:center;align-items:center;padding:15px;">
        <div style="background:#fff;border-radius:16px;width:100%;max-width:420px;box-shadow:0 15px 40px rgba(255,37,0,0.25);overflow:hidden;">
            
            <!-- Cabeçalho vermelho bonito -->
            <div style="background:#FF2500;color:white;padding:16px 20px;text-align:center;font-size:18px;font-weight:bold;">
                Editar Usuário #<span id="modal-id-display"></span>
            </div>

            <div style="padding:20px;">
                <form id="formEditarUsuario">
                    <input type="hidden" name="acao" value="editar_usuario">
                    <input type="hidden" name="id" id="edit-id">

                    <div style="display:grid;gap:12px;">

                        <input type="text" name="nome" id="edit-nome" placeholder="Nome completo" required
                            style="padding:11px;border:1px solid #ddd;border-radius:8px;font-size:14px;">

                        <input type="email" name="email" id="edit-email" placeholder="Email" required
                            style="padding:11px;border:1px solid #ddd;border-radius:8px;font-size:14px;">

                        <input type="text" name="telefone" id="edit-telefone" placeholder="Telefone" required
                            style="padding:11px;border:1px solid #ddd;border-radius:8px;font-size:14px;">

                        <input type="date" name="nascimento" id="edit-nascimento" required
                            style="padding:11px;border:1px solid #ddd;border-radius:8px;font-size:14px;">

                        <!-- URL + Preview lado a lado (compacto) -->
                        <div style="display:flex;gap:10px;align-items:center;">
                            <input type="url" name="imagem" id="edit-imagem" placeholder="URL da foto" required
                                style="flex:1;padding:11px;border:1px solid #ddd;border-radius:8px;font-size:13px;">
                            <img id="preview-imagem" src="" alt="Foto"
                                style="width:52px;height:52px;border-radius:50%;object-fit:cover;border:3px solid #FF2500;">
                        </div>

                        <select name="status" id="edit-status" style="padding:11px;border:1px solid #ddd;border-radius:8px;font-size:14px;">
                            <option value="a">Ativo</option>
                            <option value="i">Inativo</option>
                        </select>
                    </div>

                    <div style="margin-top:20px;display:flex;gap:10px;justify-content:flex-end;">
                        <button type="button" id="btn-cancelar"
                                style="margin-top: 20px;padding:10px 18px;border:none;background:#ccc;color:#333;border-radius:8px;cursor:pointer;font-weight:600;">
                            Cancelar
                        </button>
                        <button type="submit"
                                style="padding:10px 24px;border:none;background:#FF2500;color:white;border-radius:8px;cursor:pointer;font-weight:600;">
                            Salvar
                        </button>
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
    </section>

    <?php
        mysqli_close($conn); // Fecha a conexão com o banco de dados
    ?>

    <script>
        // === CADASTROS (linguagem, categoria, pergunta) ===
        document.querySelectorAll('#form-linguagem, #form-categoria, #form-pergunta').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const data = new FormData(form);

                try {
                    const response = await fetch('http://localhost/FreeCodex/Src/Services/apiAdimin.php', {
                        method: 'POST',
                        body: data
                    });
                    const result = await response.json();
                    alert(result.message);
                    if (response.ok) {
                        form.reset();
                        location.reload();
                    }
                } catch (err) {
                    alert('Erro de conexão com o servidor');
                }
            });
        });

        // === EDIÇÃO DE USUÁRIO (só esse form) ===
        document.getElementById('formEditarUsuario').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            // Adiciona a ação que estava faltando no modal (você já corrigiu, mas por segurança)
            formData.append('acao', 'editar_usuario');

            try {
                const response = await fetch('http://localhost/FreeCodex/Src/Services/apiAdimin.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                alert(result.message); // só aparece UMA vez

                if (response.ok) {
                    document.getElementById('modalEditar').style.display = 'none';
                    location.reload();
                }
            } catch (err) {
                alert('Erro ao salvar as alterações');
            }
        });

        // === ABRIR MODAL ===
        document.querySelectorAll('.btn-editar').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-id').value = this.dataset.id;
                document.getElementById('edit-nome').value = this.dataset.nome;
                document.getElementById('edit-email').value = this.dataset.email;
                document.getElementById('edit-telefone').value = this.dataset.telefone;
                document.getElementById('edit-nascimento').value = this.dataset.nascimento;
                document.getElementById('edit-imagem').value = this.dataset.imagem;
                document.getElementById('edit-status').value = this.dataset.status;
                document.getElementById('preview-imagem').src = this.dataset.imagem || 'https://via.placeholder.com/100?text=Sem+Foto';

                document.getElementById('modalEditar').style.display = 'flex';
            });
        });

        // === FECHAR MODAL ===
        document.getElementById('btn-cancelar').addEventListener('click', () => {
            document.getElementById('modalEditar').style.display = 'none';
        });

        // === PREVIEW DA IMAGEM ===
        document.getElementById('edit-imagem').addEventListener('input', function() {
            const url = this.value.trim();
            document.getElementById('preview-imagem').src = url || 'https://via.placeholder.com/100?text=Sem+Foto';
        });
    </script>
</body>
</html>